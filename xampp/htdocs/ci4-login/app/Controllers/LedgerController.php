<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountHeadModel;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LedgerController extends BaseController
{
    protected $accountHeadModel;
    protected $voucherModel;
    protected $voucherEntryModel;

    public function __construct()
    {
        $this->accountHeadModel = new AccountHeadModel();
        $this->voucherModel = new VoucherModel();
        $this->voucherEntryModel = new VoucherEntryModel();
    }

    public function accountLedger()
    {
        $tenantModel = new TenantsModel();
        $account_heads = $this->accountHeadModel->orderBy('name')->findAll();

        $selectedHead = $this->request->getGet('account_head_id');
        $fromDate     = $this->request->getGet('from_date');
        $toDate       = $this->request->getGet('to_date');

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();
            $selectedTenantId = $this->request->getGet('tenant_id');

            $builder = $this->voucherEntryModel
            ->select('vouchers.date, vouchers.voucher_number, vouchers.voucher_type, vouchers.description, voucher_entries.type, voucher_entries.amount, voucher_entries.narration, tenants.name as tenant_name')
            ->join('vouchers', 'vouchers.id = voucher_entries.voucher_id')
            ->join('tenants', 'tenants.id = vouchers.tenant_id', 'left');

            if (!empty($selectedTenantId)) {
                $builder->where('vouchers.tenant_id', $selectedTenantId);
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();
            $builder = $this->voucherEntryModel
            ->select('vouchers.date, vouchers.voucher_number, vouchers.voucher_type, vouchers.description, voucher_entries.type, voucher_entries.amount, voucher_entries.narration, tenants.name as tenant_name')
            ->join('vouchers', 'vouchers.id = voucher_entries.voucher_id')
            ->join('tenants', 'tenants.id = vouchers.tenant_id', 'left')
            ->where('vouchers.tenant_id', $tid);
        }

        if (!empty($selectedHead)) {
            $builder->where('voucher_entries.account_head_id', $selectedHead);
        }

        if (!empty($fromDate)) {
            $builder->where('vouchers.date >=', $fromDate);
        }

        if (!empty($toDate)) {
            $builder->where('vouchers.date <=', $toDate);
        }

        $ledger = $builder->orderBy('vouchers.date', 'ASC')->findAll();

        $data['account_heads'] = $account_heads;
        $data['ledger']        = $ledger;
        $data['selected_head'] = $selectedHead;
        $data['from_date']     = $fromDate;
        $data['to_date']       = $toDate;

        return view('ledger/accountLedger', $data);
    }

    public function accountLedgerExport()
    {
        $account_head_id = $this->request->getGet('account_head_id');
        $from_date       = $this->request->getGet('from_date');
        $to_date         = $this->request->getGet('to_date');
        $tenant_id       = $this->request->getGet('tenant_id');

        $builder = $this->voucherEntryModel
        ->select('
            voucher_entries.*,
            vouchers.date,
            vouchers.voucher_number,
            vouchers.voucher_type,
            vouchers.description,
            tenants.name AS tenant_name
            ')
        ->join('vouchers', 'vouchers.id = voucher_entries.voucher_id', 'left')
        ->join('tenants', 'tenants.id = voucher_entries.tenant_id', 'left');

    // Apply account head filter
        if (!empty($account_head_id)) {
            $builder->where('voucher_entries.account_head_id', $account_head_id);
        }

    // Apply tenant filter
        if (!empty($tenant_id)) {
            $builder->where('voucher_entries.tenant_id', $tenant_id);
        } elseif (!isSuperAdmin()) {
            $builder->where('voucher_entries.tenant_id', currentTenantId());
        }

    // Apply date filters on vouchers.date
        if (!empty($from_date)) $builder->where('vouchers.date >=', $from_date);
        if (!empty($to_date))   $builder->where('vouchers.date <=', $to_date);

        $ledger = $builder->orderBy('vouchers.date', 'ASC')->findAll();

    // ---------------- Excel ----------------
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Account Ledger');
        $sheet->setCellValue('A2', 'From: ' . $from_date . ' To: ' . $to_date);

    // Column headers
        $headers = ['Date', 'Voucher #', 'Voucher Type', 'Description', 'Narration', 'Debit', 'Credit', 'Tenant'];
        $sheet->fromArray([$headers], null, 'A4');
        $sheet->getStyle('A4:H4')->getFont()->setBold(true);

        $row = 5;
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($ledger as $entry) {
            $debit = $entry['type'] === 'debit' ? $entry['amount'] : 0;
            $credit = $entry['type'] === 'credit' ? $entry['amount'] : 0;

            $totalDebit += $debit;
            $totalCredit += $credit;

            $sheet->fromArray([
                $entry['date'],
                $entry['voucher_number'],
                ucfirst($entry['voucher_type']),
                $entry['description'],
                $entry['narration'],
                $debit,
                $credit,
                $entry['tenant_name'] ?? 'N/A'
            ], null, 'A' . $row);

            $row++;
        }

    // Closing totals (same as view)
        $sheet->setCellValue('E' . $row, 'Closing Balance');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('F' . $row, $totalDebit);
        $sheet->setCellValue('G' . $row, $totalCredit);

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Account_Ledger_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
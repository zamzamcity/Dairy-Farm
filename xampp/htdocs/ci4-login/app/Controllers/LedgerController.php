<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountHeadModel;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
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
        $account_heads = $this->accountHeadModel->orderBy('name')->findAll();
        $ledger = [];
        $selected_head = $this->request->getGet('account_head_id');
        $from_date = $this->request->getGet('from_date');
        $to_date = $this->request->getGet('to_date');

        if ($selected_head) {
            $builder = $this->voucherEntryModel
            ->select('vouchers.date, vouchers.voucher_number, vouchers.voucher_type, vouchers.description, voucher_entries.type, voucher_entries.amount, voucher_entries.narration')
            ->join('vouchers', 'vouchers.id = voucher_entries.voucher_id')
            ->where('voucher_entries.account_head_id', $selected_head);

            if ($from_date) {
                $builder->where('vouchers.date >=', $from_date);
            }

            if ($to_date) {
                $builder->where('vouchers.date <=', $to_date);
            }

            $ledger = $builder->orderBy('vouchers.date', 'ASC')->findAll();
        }

        return view('ledger/accountLedger', [
            'account_heads' => $account_heads,
            'ledger'        => $ledger,
            'selected_head' => $selected_head,
            'from_date'     => $from_date,
            'to_date'       => $to_date
        ]);
    }
    public function accountLedgerExport()
    {
        $account_head_id = $this->request->getGet('account_head_id');
        $from_date = $this->request->getGet('from_date');
        $to_date = $this->request->getGet('to_date');

        $builder = $this->voucherEntryModel
        ->select('vouchers.date, vouchers.voucher_number, vouchers.voucher_type, vouchers.description, voucher_entries.type, voucher_entries.amount, voucher_entries.narration')
        ->join('vouchers', 'vouchers.id = voucher_entries.voucher_id')
        ->where('voucher_entries.account_head_id', $account_head_id);

        if ($from_date) {
            $builder->where('vouchers.date >=', $from_date);
        }

        if ($to_date) {
            $builder->where('vouchers.date <=', $to_date);
        }

        $ledger = $builder->orderBy('vouchers.date', 'ASC')->findAll();
        $account_head = $this->accountHeadModel->find($account_head_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Account Ledger - ' . $account_head['name']);
        $sheet->setCellValue('A2', 'From: ' . $from_date . ' To: ' . $to_date);

    // Set column headers
        $headers = ['Date', 'Voucher #', 'Voucher Type', 'Description', 'Narration', 'Debit', 'Credit'];
        $sheet->fromArray([$headers], null, 'A4');

    // Bold header row
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);

        $row = 5;
        $balance = 0;

        foreach ($ledger as $entry) {
            $debit = $entry['type'] === 'debit' ? $entry['amount'] : 0;
            $credit = $entry['type'] === 'credit' ? $entry['amount'] : 0;
            $balance += ($debit - $credit);

            $sheet->fromArray([
                $entry['date'],
                $entry['voucher_number'],
                ucfirst($entry['voucher_type']),
                $entry['description'],
                $entry['narration'],
                $debit,
                $credit
            ], null, 'A' . $row);
            $row++;
        }

        $sheet->setCellValue('E' . $row, 'Closing Balance');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true); // Bold "Closing Balance"
        $sheet->setCellValue('F' . $row, $balance);

        $filename = 'Account_Ledger_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
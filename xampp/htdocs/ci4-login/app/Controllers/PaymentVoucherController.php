<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use App\Models\AccountHeadModel;
use App\Models\TenantsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PaymentVoucherController extends BaseController
{
    protected $voucherModel;
    protected $voucherEntryModel;
    protected $accountHeadModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
        $this->voucherEntryModel = new VoucherEntryModel();
        $this->accountHeadModel = new AccountHeadModel();
    }

    public function paymentVoucher()
    {
        $tenantModel = new TenantsModel();

        if (isSuperAdmin()) {
            $data['tenants'] = $tenantModel->findAll();

            $selectedTenantId = $this->request->getGet('tenant_id');

            if ($selectedTenantId) {
                $vouchers = $this->voucherModel
                ->select('vouchers.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = vouchers.tenant_id', 'left')
                ->where('vouchers.voucher_type', 'payment')
                ->where('vouchers.tenant_id', $selectedTenantId)
                ->orderBy('vouchers.date', 'DESC')
                ->findAll();

                $data['account_heads'] = $this->accountHeadModel
                ->where('tenant_id', $selectedTenantId)
                ->orderBy('name')
                ->findAll();
            } else {
                $vouchers = $this->voucherModel
                ->select('vouchers.*, tenants.name as tenant_name')
                ->join('tenants', 'tenants.id = vouchers.tenant_id', 'left')
                ->where('vouchers.voucher_type', 'payment')
                ->orderBy('vouchers.date', 'DESC')
                ->findAll();

                $data['account_heads'] = $this->accountHeadModel
                ->orderBy('name')
                ->findAll();
            }

            $data['selectedTenantId'] = $selectedTenantId;
        } else {
            $tid = currentTenantId();

            $vouchers = $this->voucherModel
            ->select('vouchers.*, tenants.name as tenant_name')
            ->join('tenants', 'tenants.id = vouchers.tenant_id', 'left')
            ->where('vouchers.voucher_type', 'payment')
            ->where('vouchers.tenant_id', $tid)
            ->orderBy('vouchers.date', 'DESC')
            ->findAll();

            $data['account_heads'] = $this->accountHeadModel
            ->where('tenant_id', $tid)
            ->orderBy('name')
            ->findAll();
        }

        foreach ($vouchers as &$voucher) {
            $voucher['entries'] = $this->voucherEntryModel
            ->where('voucher_id', $voucher['id'])
            ->orderBy('id', 'ASC')
            ->findAll();
        }

        $data['vouchers'] = $vouchers;

        return view('vouchers/paymentVoucher', $data);
    }

    public function addPaymentVoucher()
    {
        $data = $this->request->getPost();

        $lastVoucher = $this->voucherModel
        ->where('voucher_type', 'payment')
        ->orderBy('id', 'DESC')
        ->first();

        $lastId = $lastVoucher ? $lastVoucher['id'] + 1 : 1;
        $voucher_number = 'PV-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $voucherData = [
            'voucher_number' => $voucher_number,
            'voucher_type'   => 'payment',
            'date'           => $data['date'],
            'reference_no'   => $data['reference_no'],
            'description'    => $data['description'],
            'tenant_id'      => isSuperAdmin()
            ? ($this->request->getPost('tenant_id') !== '' ? $this->request->getPost('tenant_id') : null)
            : currentTenantId(),
            'created_by'     => session()->get('user_id'),
            'updated_by'     => session()->get('user_id'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $voucherId = $this->voucherModel->insert($voucherData);

        if ($voucherId) {
            foreach ($data['entries'] as $entry) {
                $this->voucherEntryModel->insert([
                    'voucher_id'      => $voucherId,
                    'account_head_id' => $entry['account_head_id'],
                    'type'            => $entry['type'],
                    'amount'          => $entry['amount'],
                    'narration'       => $entry['narration'] ?? null,
                    'tenant_id'       => $voucherData['tenant_id'],
                    'created_by'      => session()->get('user_id'),
                    'updated_by'      => session()->get('user_id'),
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);
            }
            return redirect()->back()->with('success', 'Payment voucher added successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add payment voucher.');
    }

    public function editPaymentVoucher($id)
    {
        if (!isSuperAdmin()) {
            $exists = $this->voucherModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $data = $this->request->getPost();

        $voucherData = [
            'voucher_type' => 'payment',
            'date'         => $data['date'],
            'reference_no' => $data['reference_no'],
            'description'  => $data['description'],
            'updated_by'   => session()->get('user_id'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        if (isSuperAdmin() && $this->request->getPost('tenant_id')) {
            $voucherData['tenant_id'] = (int) $this->request->getPost('tenant_id');
        }

        $this->voucherModel->update($id, $voucherData);

        $this->voucherEntryModel->where('voucher_id', $id)->delete();

        foreach ($data['entries'] as $entry) {
            $this->voucherEntryModel->insert([
                'voucher_id'      => $id,
                'account_head_id' => $entry['account_head_id'],
                'type'            => $entry['type'],
                'amount'          => $entry['amount'],
                'narration'       => $entry['narration'] ?? null,
                'tenant_id'       => $voucherData['tenant_id'] ?? currentTenantId(),
                'created_by'      => session()->get('user_id'),
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()->with('success', 'Payment voucher updated successfully.');
    }

    public function deletePaymentVoucher($id)
    {
        if (!isSuperAdmin()) {
            $exists = $this->voucherModel->where('id', $id)
            ->where('tenant_id', currentTenantId())
            ->first();
            if (!$exists) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }
        }

        $this->voucherModel->delete($id);
        $this->voucherEntryModel->where('voucher_id', $id)->delete();

        return redirect()->back()->with('success', 'Payment voucher deleted successfully.');
    }

    public function exportPaymentVoucher()
    {
        $tenantId = $this->request->getGet('tenant_id');

        if (isSuperAdmin()) {
            if (!empty($tenantId)) {
                $vouchers = $this->voucherModel
                ->where('voucher_type', 'payment')
                ->where('tenant_id', $tenantId)
                ->orderBy('date', 'DESC')
                ->findAll();
            } else {
                $vouchers = $this->voucherModel
                ->where('voucher_type', 'payment')
                ->orderBy('date', 'DESC')
                ->findAll();
            }
        } else {
            $vouchers = $this->voucherModel
            ->where('voucher_type', 'payment')
            ->where('tenant_id', currentTenantId())
            ->orderBy('date', 'DESC')
            ->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Voucher No')
        ->setCellValue('B1', 'Date')
        ->setCellValue('C1', 'Reference No')
        ->setCellValue('D1', 'Description')
        ->setCellValue('E1', 'Entries (Account Head - Type - Amount - Narration)')
        ->setCellValue('F1', 'Tenant ID');

        $row = 2;
        foreach ($vouchers as $voucher) {
            $entries = $this->voucherEntryModel
            ->where('voucher_id', $voucher['id'])
            ->findAll();

            $entryText = "";
            foreach ($entries as $entry) {
                $accountHead = $this->accountHeadModel->find($entry['account_head_id']);
                $entryText .= $accountHead['name'] . " - " 
                . ucfirst($entry['type']) . " - " 
                . $entry['amount'] . " (" 
                . ($entry['narration'] ?? '') . "); ";
            }

            $sheet->setCellValue('A' . $row, $voucher['voucher_number'])
            ->setCellValue('B' . $row, $voucher['date'])
            ->setCellValue('C' . $row, $voucher['reference_no'])
            ->setCellValue('D' . $row, $voucher['description'])
            ->setCellValue('E' . $row, $entryText)
            ->setCellValue('F' . $row, $voucher['tenant_id']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Payment_Vouchers_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }
}
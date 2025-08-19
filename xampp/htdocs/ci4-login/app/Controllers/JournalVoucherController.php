<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use App\Models\AccountHeadModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JournalVoucherController extends BaseController
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

    public function journalVoucher()
    {
        $vouchers = $this->voucherModel
        ->where('voucher_type', 'journal')
        ->orderBy('date', 'DESC')
        ->findAll();

        foreach ($vouchers as &$voucher) {
            $voucher['entries'] = $this->voucherEntryModel
            ->where('voucher_id', $voucher['id'])
            ->findAll();
        }

        $account_heads = $this->accountHeadModel->orderBy('name')->findAll();

        return view('vouchers/journalVoucher', [
            'vouchers' => $vouchers,
            'account_heads' => $account_heads,
        ]);
    }

    public function addJournalVoucher()
    {
        $data = $this->request->getPost();

        $lastVoucher = $this->voucherModel
        ->where('voucher_type', 'journal')
        ->orderBy('id', 'DESC')
        ->first();

        $lastId = $lastVoucher ? $lastVoucher['id'] + 1 : 1;
        $voucher_number = 'JV-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $voucherData = [
            'voucher_number' => $voucher_number,
            'voucher_type' => 'journal',
            'date'         => $data['date'],
            'reference_no' => $data['reference_no'],
            'description'  => $data['description']
        ];

        $voucherId = $this->voucherModel->insert($voucherData);

        if ($voucherId) {
            foreach ($data['entries'] as $entry) {
                $this->voucherEntryModel->insert([
                    'voucher_id'      => $voucherId,
                    'account_head_id' => $entry['account_head_id'],
                    'type'            => $entry['type'],
                    'amount'          => $entry['amount'],
                    'narration'       => $entry['narration'] ?? null
                ]);
            }
            return redirect()->back()->with('success', 'Journal voucher added successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add journal voucher.');
    }

    public function editJournalVoucher($id)
    {
        $data = $this->request->getPost();

        $voucherData = [
            'voucher_type' => 'journal',
            'date'         => $data['date'],
            'reference_no' => $data['reference_no'],
            'description'  => $data['description']
        ];

        $this->voucherModel->update($id, $voucherData);

        $this->voucherEntryModel->where('voucher_id', $id)->delete();

        foreach ($data['entries'] as $entry) {
            $this->voucherEntryModel->insert([
                'voucher_id'      => $id,
                'account_head_id' => $entry['account_head_id'],
                'type'            => $entry['type'],
                'amount'          => $entry['amount'],
                'narration'       => $entry['narration'] ?? null
            ]);
        }

        return redirect()->back()->with('success', 'Journal voucher updated successfully.');
    }

    public function deleteJournalVoucher($id)
    {
        $this->voucherModel->delete($id);
        $this->voucherEntryModel->where('voucher_id', $id)->delete();
        return redirect()->back()->with('success', 'Journal voucher deleted successfully.');
    }

    public function exportJournalVoucher()
    {
        $vouchers = $this->voucherModel
        ->where('voucher_type', 'journal')
        ->orderBy('date', 'DESC')
        ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

    // Headers
        $sheet->setCellValue('A1', 'Voucher No');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Reference No');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Entries (Account Head - Type - Amount - Narration)');

        $row = 2;
        foreach ($vouchers as $voucher) {
            $entries = $this->voucherEntryModel
            ->where('voucher_id', $voucher['id'])
            ->findAll();

            $entryText = "";
            foreach ($entries as $entry) {
                $accountHead = $this->accountHeadModel->find($entry['account_head_id']);
                $entryText .= $accountHead['name'] . " - " . ucfirst($entry['type']) . " - " . $entry['amount'] . " (" . $entry['narration'] . "); ";
            }

            $sheet->setCellValue('A' . $row, $voucher['voucher_number']);
            $sheet->setCellValue('B' . $row, $voucher['date']);
            $sheet->setCellValue('C' . $row, $voucher['reference_no']);
            $sheet->setCellValue('D' . $row, $voucher['description']);
            $sheet->setCellValue('E' . $row, $entryText);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Journal_Vouchers_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
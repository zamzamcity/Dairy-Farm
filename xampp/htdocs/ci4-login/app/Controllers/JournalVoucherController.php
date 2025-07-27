<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VoucherModel;
use App\Models\VoucherEntryModel;
use App\Models\AccountHeadModel;

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

        $voucherData = [
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
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'voucher_number',
        'voucher_type',
        'date',
        'reference',
        'description',
        'created_at',
        'updated_at'
    ];

    public function getAllPaymentVouchers()
    {
        return $this->where('voucher_type', 'payment')->orderBy('date', 'DESC')->findAll();
    }

    public function getVoucherWithEntries($id)
    {
        return $this->select('vouchers.*, voucher_entries.*, account_heads.name as account_head_name')
            ->join('voucher_entries', 'voucher_entries.voucher_id = vouchers.id')
            ->join('account_heads', 'account_heads.id = voucher_entries.account_head_id')
            ->where('vouchers.id', $id)
            ->findAll();
    }
}
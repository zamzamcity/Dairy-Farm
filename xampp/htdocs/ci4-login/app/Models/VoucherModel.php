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
        'reference_no',
        'description',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    public function getAllPaymentVouchers($tenantId = null)
    {
        $builder = $this->where('voucher_type', 'payment')->orderBy('date', 'DESC');
        if ($tenantId) {
            $builder->where('tenant_id', $tenantId);
        }
        return $builder->findAll();
    }

    public function getVoucherWithEntries($id, $tenantId = null)
    {
        $builder = $this->select('vouchers.*, voucher_entries.*, account_heads.name as account_head_name')
            ->join('voucher_entries', 'voucher_entries.voucher_id = vouchers.id')
            ->join('account_heads', 'account_heads.id = voucher_entries.account_head_id')
            ->where('vouchers.id', $id);

        if ($tenantId) {
            $builder->where('vouchers.tenant_id', $tenantId);
        }

        return $builder->findAll();
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherEntryModel extends Model
{
    protected $table = 'voucher_entries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'voucher_id',
        'account_head_id',
        'type',
        'amount',
        'narration',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    public function getEntriesByVoucher($voucherId, $tenantId = null)
    {
        $builder = $this->select('voucher_entries.*, account_heads.name as account_head_name')
            ->join('account_heads', 'account_heads.id = voucher_entries.account_head_id')
            ->where('voucher_entries.voucher_id', $voucherId);

        if ($tenantId) {
            $builder->where('voucher_entries.tenant_id', $tenantId);
        }

        return $builder->findAll();
    }
}

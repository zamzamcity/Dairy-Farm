<?php

namespace App\Models;
use CodeIgniter\Model;

class AccountHeadModel extends Model
{
    protected $table = 'account_heads';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'account_code',
        'name',
        'type',
        'opening_balance',
        'description',
        'created_at',
        'updated_at',
        'linked_user_id',
        'tenant_id',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
}
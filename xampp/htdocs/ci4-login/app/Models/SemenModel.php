<?php

namespace App\Models;

use CodeIgniter\Model;

class SemenModel extends Model
{
    protected $table = 'semen';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sire_name',
        'rate_per_semen',
        'company_id',
        'type',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmHeadModel extends Model
{
    protected $table = 'farm_head';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'head_name',
        'tenant_id',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
}
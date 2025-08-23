<?php

namespace App\Models;

use CodeIgniter\Model;

class StockUnitModel extends Model
{
    protected $table = 'stock_units';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
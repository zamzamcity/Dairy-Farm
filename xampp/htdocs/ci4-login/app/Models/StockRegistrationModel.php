<?php

namespace App\Models;

use CodeIgniter\Model;

class StockRegistrationModel extends Model
{
    protected $table            = 'stock_registration';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'tenant_id',
        'product_name',
        'head_id',
        'unit_id',
        'is_stock_item',
        'opening_stock_qty',
        'opening_stock_rate_per_unit',
        'rate_per_unit',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
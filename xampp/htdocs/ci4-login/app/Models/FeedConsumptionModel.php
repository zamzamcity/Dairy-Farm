<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedConsumptionModel extends Model
{
    protected $table            = 'feed_consumption';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'tenant_id',
        'product_id',
        'quantity',
        'date',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps    = true;

    protected $validationRules  = [
        'tenant_id'  => 'required|integer',
        'product_id' => 'required|integer',
        'quantity'   => 'required|decimal',
        'date'       => 'required|valid_date',
    ];
}
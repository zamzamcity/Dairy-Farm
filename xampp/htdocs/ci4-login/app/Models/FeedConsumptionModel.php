<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedConsumptionModel extends Model
{
    protected $table            = 'feed_consumption';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['product_id', 'quantity', 'date'];
    protected $useTimestamps    = true; // If you added created_at, updated_at columns

    // Optional validation rules
    protected $validationRules = [
        'product_id' => 'required|integer',
        'quantity'   => 'required|decimal',
        'date'       => 'required|valid_date',
    ];
}
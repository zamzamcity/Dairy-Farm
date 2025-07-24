<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineConsumptionModel extends Model
{
    protected $table            = 'medicine_consumption';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['product_id', 'quantity', 'date'];
    protected $useTimestamps    = true; // Optional

    protected $validationRules = [
        'product_id' => 'required|integer',
        'quantity'   => 'required|decimal',
        'date'       => 'required|valid_date',
    ];
}
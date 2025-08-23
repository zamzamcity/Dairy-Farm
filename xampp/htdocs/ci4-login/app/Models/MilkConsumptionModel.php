<?php

namespace App\Models;

use CodeIgniter\Model;

class MilkConsumptionModel extends Model
{
    protected $table = 'milk_consumption';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'date',
        'farm_head_id',
        'milk_litres',
        'tenant_id',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
}
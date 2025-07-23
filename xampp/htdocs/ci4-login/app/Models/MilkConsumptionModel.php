<?php

namespace App\Models;

use CodeIgniter\Model;

class MilkConsumptionModel extends Model
{
    protected $table = 'milk_consumption';
    protected $allowedFields = ['date', 'farm_head_id', 'milk_litres'];
}
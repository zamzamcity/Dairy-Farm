<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalMilkModel extends Model
{
    protected $table            = 'animal_milk';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'date',
        'animal_id',
        'first_calving_date',
        'last_calving_date',
        'milk_1',
        'milk_2',
        'milk_3',
        'tenant_id',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
}

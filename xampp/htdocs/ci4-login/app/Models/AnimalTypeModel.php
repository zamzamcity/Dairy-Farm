<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalTypeModel extends Model
{
    protected $table = 'animal_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}

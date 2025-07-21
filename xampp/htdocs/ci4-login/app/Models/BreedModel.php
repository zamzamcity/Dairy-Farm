<?php

namespace App\Models;

use CodeIgniter\Model;

class BreedModel extends Model
{
    protected $table = 'breeds';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'animal_type_id'];
}


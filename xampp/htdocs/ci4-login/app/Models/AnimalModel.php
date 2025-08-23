<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalModel extends Model
{
    protected $table = 'animals';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pen_id', 
        'tag_id', 
        'electronic_id', 
        'name', 
        'animal_type_id', 
        'breed_id',
        'company_id', 
        'country_id', 
        'sex', 
        'status', 
        'insertion_date', 
        'birth_date',
        'price', 
        'pedigree_info', 
        'picture',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at', 
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
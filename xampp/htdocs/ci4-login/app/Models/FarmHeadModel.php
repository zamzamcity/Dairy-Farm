<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmHeadModel extends Model
{
    protected $table = 'farm_head';
    protected $primaryKey = 'id';
    protected $allowedFields = ['head_name', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
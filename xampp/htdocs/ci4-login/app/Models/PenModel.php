<?php

namespace App\Models;

use CodeIgniter\Model;

class PenModel extends Model
{
    protected $table = 'pens';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}
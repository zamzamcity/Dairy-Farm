<?php

namespace App\Models;

use CodeIgniter\Model;

class DewormingModel extends Model
{
    protected $table = 'deworming';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
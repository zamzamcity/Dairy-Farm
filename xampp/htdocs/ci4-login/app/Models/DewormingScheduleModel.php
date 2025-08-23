<?php

namespace App\Models;

use CodeIgniter\Model;

class DewormingScheduleModel extends Model
{
    protected $table = 'deworming_schedules';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'month',
        'date',
        'deworming_id',
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
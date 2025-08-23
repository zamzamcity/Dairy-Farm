<?php

namespace App\Models;

use CodeIgniter\Model;

class TechnicianModel extends Model
{
    protected $table = 'technicians';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'status',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'      => 'required|min_length[2]|max_length[100]',
        'status'    => 'in_list[Active,Inactive]',
    ];
}

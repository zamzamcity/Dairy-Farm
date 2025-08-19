<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'designation',
        'salary_type',
        'salary_amount',
        'joining_date',
        'is_active',
        'permission_group_id',
        'tenant_id',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

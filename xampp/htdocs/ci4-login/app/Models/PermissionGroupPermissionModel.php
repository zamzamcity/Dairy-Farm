<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionGroupPermissionModel extends Model
{
    protected $table = 'permission_group_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'permission_group_id',
        'permission_id',
        'tenant_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
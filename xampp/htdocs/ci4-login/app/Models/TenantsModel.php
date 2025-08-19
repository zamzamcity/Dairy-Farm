<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantsModel extends Model
{
    protected $table            = 'tenants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'name',
        'status',        // active/inactive
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get only active tenants
     */
    public function getActiveTenants()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get only inactive tenants
     */
    public function getInactiveTenants()
    {
        return $this->where('status', 'inactive')->findAll();
    }

    /**
     * Activate a tenant
     */
    public function activateTenant($id)
    {
        return $this->update($id, ['status' => 'active']);
    }

    /**
     * Deactivate a tenant
     */
    public function deactivateTenant($id)
    {
        return $this->update($id, ['status' => 'inactive']);
    }
}
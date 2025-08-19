<?php

use CodeIgniter\Config\Services;

if (! function_exists('currentTenantId')) {
    function currentTenantId(): ?int
    {
        return session()->get('tenant_id');
    }
}

if (! function_exists('isSuperAdmin')) {
    function isSuperAdmin(): bool
    {
        $role = strtolower((string) session()->get('role'));
        return $role === 'superadmin' || hasPermission('CanViewTenants');
    }
}
<?php

use CodeIgniter\Config\Services;

if (!function_exists('has_permission')) {
    function hasPermission(string $name): bool
    {
        $session = Services::session();

        $permissions = $session->get('user_permissions');

        if (!is_array($permissions)) {
            return false;
        }

        return in_array($name, $permissions);
    }
}
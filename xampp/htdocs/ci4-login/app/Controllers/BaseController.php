<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\PermissionGroupPermissionModel;

abstract class BaseController extends Controller
{
    /**
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Auto-loaded helpers
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'session', 'permission'];

    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Load session
        $this->session = \Config\Services::session();

        // Optional: Load permissions into session if logged in
        if ($this->session->has('user_id')) {
            if (!$this->session->has('user_permissions')) {
                $this->loadUserPermissions();
            }
        }
    }

    /**
     * Loads user permissions based on assigned group
     */
    protected function loadUserPermissions()
    {
        $userId = $this->session->get('user_id');
        $groupId = $this->session->get('permission_group_id');

        if (!$groupId) {
            $this->session->set('user_permissions', []);
            return;
        }

        $permModel = new PermissionGroupPermissionModel();

        $permissions = $permModel
        ->select('permissions.name')
        ->join('permissions', 'permissions.id = permission_group_permissions.permission_id')
        ->where('permission_group_permissions.permission_group_id', $groupId)
        ->findAll();

        $names = array_column($permissions, 'name');

        $this->session->set('user_permissions', $names);
    }
}
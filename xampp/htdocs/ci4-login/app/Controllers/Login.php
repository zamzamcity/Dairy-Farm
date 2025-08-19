<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('username')) {
            return redirect()->to('/login/home');
        }

        return view('login');
    }

    public function auth()
    {
        $session = session();
        $model = new \App\Models\UserModel();
        $db = \Config\Database::connect();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if ($user['is_active'] != 1) {
                return redirect()->back()->with('error', 'Your account is inactive. Please contact administrator.');
            }
            
            if (password_verify($password, $user['password'])) {
                $permissions = $db->table('permission_group_permissions')
                ->select('permissions.name')
                ->join('permissions', 'permissions.id = permission_group_permissions.permission_id')
                ->where('permission_group_permissions.permission_group_id', $user['permission_group_id'])
                ->get()
                ->getResultArray();

                $permissionNames = array_column($permissions, 'name');

                $session->set([
                    'user_id' => $user['id'],
                    'tenant_id' => $user['tenant_id'],
                    'created_by' => $user['created_by'],
                    'permission_group_id' => $user['permission_group_id'],
                    'email' => $user['email'],
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'role' => $user['role'],
                    'user_permissions' => $permissionNames,
                ]);
                if (! isSuperAdmin()) {
                    $tenant = $db->table('tenants')->where('id', $user['tenant_id'])->get()->getRow();

                    if (!$tenant) {
                        return redirect()->back()->with('error', 'Your tenant account does not exist.');
                    }

                    if ($tenant->status != 'active') {
                        return redirect()->back()->with('error', 'Your tenant account is inactive. Please contact administrator.');
                    }
                }
                return redirect()->to('/login/home');
            } else {
                return redirect()->back()->with('error', 'Incorrect password.');
            }
        } else {
            return redirect()->back()->with('error', 'User not found.');
        }
    }

    public function store()
    {
        helper(['form']);
        $model = new \App\Models\UserModel();

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role') ?? 'user',
            'created_by' => session()->get('user_id') ?? null,
        ];

        if (isSuperAdmin()) {
            $data['tenant_id'] = null;
        } else {
            $data['tenant_id'] = $this->request->getPost('tenant_id');
        }

        $model->save($data);

        return redirect()->to('/pages/login')->with('success', 'Registered successfully');
    }

    public function home()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        $userModel = new \App\Models\UserModel();
        $totalEmployees = $userModel->where('is_active', 1)->countAllResults();

        $animalModel = new \App\Models\AnimalModel();
        $totalAnimals = $animalModel->countAllResults();

        $db = \Config\Database::connect();

        $milkResult = $db->table('daily_milking')
        ->selectSum('total_milk')
        ->get()
        ->getRow();

        $totalMilk = $milkResult->total_milk ?? 0;

        $totalProducts = $db->table('stock_registration')->countAll();

        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'totalAnimals' => $totalAnimals,
            'totalMilk' => $totalMilk,
            'totalProducts' => $totalProducts,
            'title' => 'Zam Zam DairyCare - Dashboard'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function register()
    {
        return view('register'); 
    }

}
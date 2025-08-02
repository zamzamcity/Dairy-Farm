<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        // Redirect to dashboard if already logged in
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

            if ($user['password'] === $password) {
                $permissions = $db->table('permission_group_permissions')
                ->select('permissions.name')
                ->join('permissions', 'permissions.id = permission_group_permissions.permission_id')
                ->where('permission_group_permissions.permission_group_id', $user['permission_group_id'])
                ->get()
                ->getResultArray();

                $permissionNames = array_column($permissions, 'name');

                $session->set([
                    'user_id' => $user['id'],
                    'permission_group_id' => $user['permission_group_id'],
                    'email' => $user['email'],
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'role' => $user['role'],
                    'user_permissions' => $permissionNames,
                ]);

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
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role') ?? 'user',
        ];

        $model->save($data);

        return redirect()->to('/pages/login')->with('success', 'Registered successfully');
    }

    public function home()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        return view('dashboard');
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
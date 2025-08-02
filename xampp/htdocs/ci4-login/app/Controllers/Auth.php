<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;

class Auth extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        $user = $this->db->table('users')
        ->where('email', $email)
        ->where('is_active', 1)
        ->get()
        ->getRow();

        if ($user) {
            $token = bin2hex(random_bytes(32));

            $this->db->table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $resetLink = base_url("auth/reset-password/" . $token);

            return redirect()->back()->with('message', 'Reset link: ' . $resetLink);
        } else {
            return redirect()->back()->with('error', 'Email not found or inactive!');
        }
    }

}
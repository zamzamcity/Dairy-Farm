<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;

class Auth extends Controller
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

            return redirect()->back()->with('message', 'Reset link: <a href="' . $resetLink . '">' . $resetLink . '</a>');
        } else {
            return redirect()->back()->with('error', 'Email not found or inactive!');
        }
    }
    public function resetPassword($token)
    {
        $builder = $this->db->table('password_resets');
        $reset = $builder->where('token', $token)->get()->getRow();

        if (!$reset) {
            return redirect()->to('login')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth/reset-password', ['token' => $token]);
    }
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirm = $this->request->getPost('password_confirm');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $builder = $this->db->table('password_resets');
        $reset = $builder->where('token', $token)->get()->getRow();

        if (!$reset) {
            return redirect()->to('login')->with('error', 'Invalid or expired reset token.');
        }

        $userBuilder = $this->db->table('users');
        $userBuilder->where('email', $reset->email)->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $builder->where('email', $reset->email)->delete();

        return redirect()->to('login')->with('success', 'Password updated successfully.');
    }

}
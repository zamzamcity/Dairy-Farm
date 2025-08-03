<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRow();

        if (!$user) {
            return redirect()->to('login');
        }

        return view('profile', ['user' => $user]);
    }
}
<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function colors()
    {
        return view('utilities-color');
    }

    public function borders()
    {
        return view('utilities-border');
    }

    public function animations()
    {
        return view('utilities-animation');
    }

    public function others()
    {
        return view('utilities-other');
    }
    public function login()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function blank()
    {
        return view('blank');
    }

    public function errorPage()
    {
        return view('404');
    }

}

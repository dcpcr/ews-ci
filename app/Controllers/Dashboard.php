<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    use \Myth\Auth\AuthTrait;

    public function __construct()
    {
        $this->restrict( site_url('/') );
    }

    public function index()
    {
        return view('welcome_message');
    }


}

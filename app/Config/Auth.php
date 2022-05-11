<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends \Myth\Auth\Config\Auth
{
    public $views = [
        'login' => 'App\Views\login',
    ];

    public $allowRegistration = false;

    public $activeResetter = null;

    public $viewLayout = 'App\Views\layout';

    public $validFields = [
        'username',
    ];

    public $requireActivation = false;
}

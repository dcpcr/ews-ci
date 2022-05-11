<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    use \Myth\Auth\AuthTrait;

    protected $helpers = ['auth'];

    public function __construct()
    {
        $this->restrict();
        $this->setupAuthClasses();
    }
}

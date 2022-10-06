<?php

namespace App\Controllers;

use CodeIgniter\Router\Exceptions\RedirectException;

class AuthController extends BaseController
{
    use \Myth\Auth\AuthTrait;

    protected $helpers = ['auth'];

    /**
     * @throws RedirectException
     */
    public function __construct()
    {
        $this->restrict();
        $this->setupAuthClasses();
    }
}

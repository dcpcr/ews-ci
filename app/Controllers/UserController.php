<?php

namespace App\Controllers;

class UserController extends AuthController
{
    public function index()
    {
        if ($this->checkForOperator()) {
            return redirect()->route('operator');
        } else {
            return redirect()->route('dashboard', ['case']);
        }
    }

    private function checkForOperator() {
        return false;
        //TODO: Add the code for validation for operator permissions
    }
}

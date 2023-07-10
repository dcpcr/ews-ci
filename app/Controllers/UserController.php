<?php

namespace App\Controllers;

class UserController extends AuthController
{
    public function index()
    {
        if ($this->checkForOperator()) {
            return redirect()->route('operator');
        }
        elseif (has_permission('viewReportsSchool')) {
            return redirect()->route('dashboard', ['home']);
        }
        else{
            return redirect()->route('dashboard', ['summary']);
        }
    }

    private function checkForOperator() {
        return false;
        //TODO: Add the code for validation for operator permissions
    }
}

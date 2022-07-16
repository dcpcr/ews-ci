<?php

namespace App\Models;

use Myth\Auth\Models\LoginModel as AuthLoginModel;

class LoginModel extends AuthLoginModel
{
    protected $validationRules = [
        'ip_address' => 'required',
        'email'      => 'permit_empty',
        'user_id'    => 'permit_empty|integer',
        'date'       => 'required|valid_date',
    ];
}

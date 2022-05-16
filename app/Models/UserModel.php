<?php

namespace App\Models;

use Myth\Auth\Models\UserModel as AuthUserModel;

class UserModel extends AuthUserModel
{
    protected $validationRules = [
        'username'      => 'required|alpha_numeric_punct|min_length[2]|max_length[30]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
    ];

    protected $allowedFields  = [
        'email', 'username', 'firstname', 'lastname', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'deleted_at',
    ];

    protected $returnType = 'App\Entities\User';
}

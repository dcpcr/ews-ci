<?php

namespace App\Validation;

use App\Models\ApiUserModel;
use Exception;

class ApiUserRules
{
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new ApiUserModel();
            $user = $model->findUser($data['username']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }
}
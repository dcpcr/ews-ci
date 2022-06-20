<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ApiUserModel extends Model
{
    protected $table = 'api_user';
    protected $allowedFields = [
        'name',
        'username',
        'password',
    ];
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    public function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }

    public function findUser(string $username)
    {
        $user = $this
            ->asArray()
            ->where(['username' => $username])
            ->first();

        if (!$user)
            throw new Exception('User does not exist for specified username');
        return $user;
    }
}
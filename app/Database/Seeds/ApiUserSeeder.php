<?php

namespace App\Database\Seeds;

use App\Models\ApiUserModel;
use CodeIgniter\Database\Seeder;

class ApiUserSeeder extends Seeder
{
    /**
     * @throws \ReflectionException
     */
    public function run()
    {
        $user_model = new ApiUserModel();

        $rows = array(
            array('name' => 'Cyfuture India Pvt Ltd', 'username' => 'cyfuture', 'password' => '111111111')
        );

        foreach ($rows as $row) {
            $user = $user_model->where('username', $row['name'])->first();
            if (empty($district)) {
                $user_model->insert($row);
            }
        }
    }
}

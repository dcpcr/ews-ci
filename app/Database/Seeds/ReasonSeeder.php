<?php

namespace App\Database\Seeds;

use App\Models\ReasonModel;
use CodeIgniter\Database\Seeder;

class ReasonSeeder extends Seeder
{
    public function run()
    {

        $reason_model = new ReasonModel();


        $rows = array(
            array('id' => '1', 'name' => 'Moved back to village/ different State', 'active' => '1'),
            array('id' => '2', 'name' => 'Sickness', 'active' => '1'),
            array('id' => '3', 'name' => 'Any other reason', 'active' => '1'),
            array('id' => '4', 'name' => 'Denial of admission / registration / name struck out', 'active' => '1'),
            array('id' => '5', 'name' => 'Denial of resources (book/uniform) / Did not receive subsidy', 'active' => '1'),
            array('id' => '6', 'name' => 'No interest in studies', 'active' => '1'),
            array('id' => '7', 'name' => 'Infrastructure/ Resource-related', 'active' => '1'),
            array('id' => '8', 'name' => 'Sexual offence or sexually inappropriate behavior towards the student', 'active' => '1'),
            array('id' => '9', 'name' => 'Missing Child', 'active' => '1'),
            array('id' => '10', 'name' => 'Peer pressure', 'active' => '1'),
            array('id' => '11', 'name' => 'Incarceration', 'active' => '1'),
            array('id' => '12', 'name' => 'Corporal punishment', 'active' => '1')

        );
        foreach ($rows as $row) {
            $reason = $reason_model->where('id', $row['id'])->first();
            if (empty($reason)) {
                $reason_model->insert($row);
            }
        }
    }
}

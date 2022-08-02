<?php

namespace App\Database\Seeds;

use App\Models\CallDispositionMasterModel;
use CodeIgniter\Database\Seeder;

class CallDispositionMasterSeeder extends Seeder
{
    /**
     * @throws \ReflectionException
     */
    public function run()
    {
        $callDispositionMasterModel= new CallDispositionMasterModel();


        $rows = array(
            array('id' => '1', 'name' => 'Call Connected', 'active' => '1'),
            array('id' => '2', 'name' => 'No Answer', 'active' => '1'),
            array('id' => '3', 'name' => 'Wrong Number', 'active' => '1'),
            array('id' => '4', 'name' => 'Call Busy', 'active' => '1'),
            array('id' => '5', 'name' => 'Call Disconnected (Without Picking)', 'active' => '1'),
            array('id' => '6', 'name' => 'Call Disconnected (After Picking)', 'active' => '1'),
            array('id' => '7', 'name' => 'Not Reachable/Out of Network', 'active' => '1'),
            array('id' => '8', 'name' => 'Received Incomplete Information', 'active' => '1'),
            array('id' => '9', 'name' => 'Switched Off', 'active' => '1'),

        );
        foreach ($rows as $row) {
            $callDisposition = $callDispositionMasterModel->where('id', $row['id'])->first();
            if (empty($callDisposition)) {
                $callDispositionMasterModel->insert($row);
            }
        }

    }
}

<?php

namespace App\Database\Seeds;

use App\Models\DcpcrSubDivisionMasterModel;
use CodeIgniter\Database\Seeder;

class DcpcrSubDivisionMasterSeeder extends Seeder
{
    public function run()
    {
        $dcpcrDivisionMasterModel = new DcpcrSubDivisionMasterModel();
        $rows = array(
            array('id' => '2', 'division_id' => '1', 'subdivision_name' => 'Corporal Punishment', 'active' => '1'),
            array('id' => '3', 'division_id' => '1', 'subdivision_name' => 'Denial of Admission', 'active' => '1'),
            array('id' => '4', 'division_id' => '1', 'subdivision_name' => 'Denial of Books & Uniform', 'active' => '1'),
            array('id' => '5', 'division_id' => '1', 'subdivision_name' => 'Mental Health', 'active' => '1'),
            array('id' => '6', 'division_id' => '1', 'subdivision_name' => 'Others', 'active' => '1'),
            array('id' => '7', 'division_id' => '1', 'subdivision_name' => 'Private School fees', 'active' => '1'),
            array('id' => '8', 'division_id' => '1', 'subdivision_name' => 'School Infrastructure', 'active' => '1'),
            array('id' => '9', 'division_id' => '2', 'subdivision_name' => 'Immunization', 'active' => '1'),
            array('id' => '10', 'division_id' => '2', 'subdivision_name' => 'Others', 'active' => '1'),
            array('id' => '11', 'division_id' => '2', 'subdivision_name' => 'General Medical Issue', 'active' => '1'),
            array('id' => '1', 'division_id' => '3', 'subdivision_name' => 'Child Labour', 'active' => '1'),
            array('id' => '12', 'division_id' => '4', 'subdivision_name' => 'Abandon Child', 'active' => '1'),
            array('id' => '13', 'division_id' => '4', 'subdivision_name' => 'Child Marriage', 'active' => '1'),
            array('id' => '14', 'division_id' => '4', 'subdivision_name' => 'Others', 'active' => '1'),
            array('id' => '15', 'division_id' => '4', 'subdivision_name' => 'Substance Abuse', 'active' => '1'),
            array('id' => '16', 'division_id' => '5', 'subdivision_name' => 'Missing Child', 'active' => '1'),
            array('id' => '17', 'division_id' => '6', 'subdivision_name' => 'POCSO', 'active' => '1'),


        );
        foreach ($rows as $row) {
            $divisionMaster = $dcpcrDivisionMasterModel->where('id', $row['id'])->first();
            if (empty($divisionMaster)) {
                $dcpcrDivisionMasterModel->insert($row);
            }
        }

    }
}

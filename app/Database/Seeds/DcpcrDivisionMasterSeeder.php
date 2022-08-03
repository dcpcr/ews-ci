<?php

namespace App\Database\Seeds;

use App\Models\DcpcrDivisionMasterModel;
use CodeIgniter\Database\Seeder;

class DcpcrDivisionMasterSeeder extends Seeder
{
    public function run()
    {
        $dcpcrDivisionMasterModel = new DcpcrDivisionMasterModel();
        $rows = array(
            array('id' => '1', 'division_name' => 'Education', 'active' => '1'),
            array('id' => '2', 'division_name' => 'Health & Nutrition', 'active' => '1'),
            array('id' => '3', 'division_name' => 'Child Labour', 'active' => '1'),
            array('id' => '4', 'division_name' => 'Juvenile Justice', 'active' => '1'),
            array('id' => '5', 'division_name' => 'Missing', 'active' => '1'),
            array('id' => '6', 'division_name' => 'POCSO', 'active' => '1'),
            array('id' => '7', 'division_name' => 'Others', 'active' => '1'),


        );
        foreach ($rows as $row) {
            $divisionMaster = $dcpcrDivisionMasterModel->where('id', $row['id'])->first();
            if (empty($divisionMaster)) {
                $dcpcrDivisionMasterModel->insert($row);
            }
        }

    }
}

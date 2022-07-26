<?php

namespace App\Database\Seeds;
use App\Models\BackToSchoolMasterModel;
use CodeIgniter\Database\Seeder;

class BackToSchoolMasterSeeder extends Seeder
{
    /**
     * @throws \ReflectionException
     */
    public function run()
    {
        $backToSchoolMasterModel = new BackToSchoolMasterModel();

        $rows = array(
            array('id' => '1', 'name' => 'Student is back to school', 'active' => '1'),
            array('id' => '2', 'name' => 'Student will soon return back to school', 'active' => '1'),
            array('id' => '3', 'name' => 'Respondent is unsure', 'active' => '1'),
            array('id' => '4', 'name' => 'Student will not return back to school', 'active' => '1'),

        );

        foreach ($rows as $row) {
            $backToSchool = $backToSchoolMasterModel->where('id', $row['id'])->first();
            if (empty($backToSchool)) {
                $backToSchoolMasterModel->insert($row);
            }
        }

    }
}

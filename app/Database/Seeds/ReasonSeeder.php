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
            array('id' => '1', 'name' => 'Sickness', 'active' => '1'),
            array('id' => '2', 'name' => 'Incarceration', 'active' => '1'),
            array('id' => '3', 'name' => 'Moved Back to Village/ Different State', 'active' => '1'),
            array('id' => '4', 'name' => 'Sexual Offences or Sexually Inappropriate Behaviour towards the student', 'active' => '1'),
            array('id' => '5', 'name' => 'Child Marriage', 'active' => '1'),
            array('id' => '6', 'name' => 'Denial of Admission/ Registration/ Name Struck Out', 'active' => '1'),
            array('id' => '7', 'name' => 'Denial of Resources (book/uniform) / Did not Receive Subsidy', 'active' => '1'),
            array('id' => '8', 'name' => 'Corporal Punishment', 'active' => '1'),
            array('id' => '9', 'name' => 'Infrastructure/ Resource Related', 'active' => '1'),
            array('id' => '10', 'name' => 'Mid Day Meal', 'active' => '1'),
            array('id' => '11', 'name' => 'No Interest in Studies/ not Interested in Going to School (Mental health)', 'active' => '1'),
            array('id' => '12', 'name' => 'Peer pressure', 'active' => '1'),
            array('id' => '13', 'name' => 'Substance abuse', 'active' => '1'),
            array('id' => '14', 'name' => 'Missing Child', 'active' => '1'),
            array('id' => '15', 'name' => 'Child Labour', 'active' => '1'),
            array('id' => '16', 'name' => 'Any other reason', 'active' => '1'),
            array('id' => '17', 'name' => 'Want to Change School / Already Changed School', 'active' => '1'),
            array('id' => '18', 'name' => 'Disability', 'active' => '1'),
            array('id' => '19', 'name' => 'Bullying/ Physical Abuse', 'active' => '1'),
            array('id' => '20', 'name' => 'Respondent Denied Absenteeism', 'active' => '1'),
            array('id' => '21', 'name' => 'Respondent is Unaware of Absenteeism', 'active' => '1'),
            array('id' => '22', 'name' => 'Death', 'active' => '1')


        );
        foreach ($rows as $row) {
            $reason = $reason_model->where('id', $row['id'])->first();
            if (empty($reason)) {
                $reason_model->insert($row);
            }
        }
    }
}

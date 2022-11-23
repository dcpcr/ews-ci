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
            array(
                'id' => '1',
                'name' => 'Sickness',
                'active' => '1',
                'action_taken' => 'Monitor early identification of sickness via school clinics and refer students to hospitals for treatment. Reach out to DCPCR or Childline (1098) in case a child needs serious medical support.'
            ),
            array(
                'id' => '2',
                'name' => 'Incarceration',
                'active' => '1',
                'action_taken' => 'Reach out to DCPCR or Child Line (1098) for providing support to the child.'
            ),
            array(
                'id' => '3',
                'name' => 'Moved Back to Village/ Different State',
                'active' => '1',
                'action_taken' => 'Ensure that these students have received their transfer certificate/school leaving certificate.'
            ),
            array(
                'id' => '4',
                'name' => 'Sexual Offences or Sexually Inappropriate Behaviour towards the student',
                'active' => '1',
                'action_taken' => 'Reach out to Delhi Police or DCPCR for providing support to the child.'
            ),
            array(
                'id' => '5',
                'name' => 'Child Marriage',
                'active' => '1',
                'action_taken' => 'Reach out to Delhi Police or DCPCR for providing support to the child.'
            ),
            array(
                'id' => '6',
                'name' => 'Denial of Admission/ Registration/ Name Struck Out',
                'active' => '1',
                'action_taken' => 'Ensure that every student’s school attendance is not hampered because of administrative issues.'
            ),
            array(
                'id' => '7',
                'name' => 'Denial of Resources (book/uniform) / Did not Receive Subsidy',
                'active' => '1',
                'action_taken' => 'Ensure that every student receives all the resources/subsidies.'
            ),
            array(
                'id' => '8',
                'name' => 'Corporal Punishment',
                'active' => '1',
                'action_taken' => 'Ensure that no teacher is physically or mentally harassing the students.'
            ),
            array(
                'id' => '9',
                'name' => 'Infrastructure/ Resource Related',
                'active' => '1',
                'action_taken' => 'Ensure that no infrastructure and resources related issues are hampering a student’s school attendance.'
            ),
            array(
                'id' => '10',
                'name' => 'Mid Day Meal',
                'active' => '1',
                'action_taken' => 'Ensure optimum quality and regular supply of mid-day meal.'
            ),
            array(
                'id' => '11',
                'name' => 'No Interest in Studies/ not Interested in Going to School (Mental health)',
                'active' => '1',
                'action_taken' => 'Provide counselling support to students via school counsellors.'
            ),
            array(
                'id' => '12',
                'name' => 'Peer pressure',
                'active' => '1',
                'action_taken' => 'Ensure close monitoring by class teachers & take early action to prevent frequent absenteeism due to peer pressure.'
            ),
            array(
                'id' => '13',
                'name' => 'Substance abuse',
                'active' => '1',
                'action_taken' => 'Reach out to DCPCR or Childline (1098) for providing support to the child.'
            ),
            array(
                'id' => '14',
                'name' => 'Missing Child',
                'active' => '1',
                'action_taken' => 'Reach out to Delhi Police or DCPCR for tracing the child.'
            ),
            array(
                'id' => '15',
                'name' => 'Child Labour',
                'active' => '1',
                'action_taken' => 'Reach out to DCPCR or Childline (1098) for providing support to the child.'
            ),
            array(
                'id' => '16',
                'name' => 'Any other reason',
                'active' => '1',
                'action_taken' => ''
            ),
            array(
                'id' => '17',
                'name' => 'Want to Change School',
                'active' => '1',
                'action_taken' => 'Provide the required support for ensuring smooth transition to another school.'
            ),
            array(
                'id' => '18',
                'name' => 'Disability',
                'active' => '1',
                'action_taken' => 'Ensure adequate services and support is being provided in the school for disabled children.'
            ),
            array(
                'id' => '19',
                'name' => 'Bullying/ Physical Abuse',
                'active' => '1',
                'action_taken' => 'Ensure close monitoring by class teachers & take early action to prevent bullying/physical abuse.'
            ),
            array(
                'id' => '20',
                'name' => 'Respondent Denied Absenteeism',
                'active' => '1',
                'action_taken' => 'Ensure that the parents/guardians are aware of the student’s uninformed absenteeism on a daily basis.'
            ),
            array(
                'id' => '21',
                'name' => 'Respondent is Unaware of Absenteeism',
                'active' => '1',
                'action_taken' => 'Ensure that the parents/guardians are aware of the student’s uninformed absenteeism on a daily basis.'
            ),
            array(
                'id' => '22',
                'name' => 'Death',
                'active' => '1',
                'action_taken' => ''
            ),
            array(
                'id' => '23',
                'name' => 'Already Changed School',
                'active' => '1',
                'action_taken' => ''
            )


        );
        foreach ($rows as $row) {
            $reason = $reason_model->where('id', $row['id'])->first();
            if (empty($reason)) {
                $reason_model->insert($row);
            }
        }
    }
}

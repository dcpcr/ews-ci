<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CaseModel;
use App\Models\StudentModel;

class DataUpdateController extends BaseController
{
    public function updateStudentDataInDetectedCaseTable()
    {
        $case_model = new CaseModel();
        $students_ids = $case_model->fetchIncompleteDetailStudentIds();
        $student_model = new StudentModel();
        $count=0;
        foreach ($students_ids as $students_id) {
            $student_details = $student_model->fetchStudentDetails($students_id['student_id']);
            if ($student_details !== null) {
                $case_ids = $case_model->getCaseIds($students_id);
                $case_model->updateDetectedStudentDetails($student_details, $case_ids);
                $count++;
            }
        }
        log_message("info","Total students updated: $count");
    }
}

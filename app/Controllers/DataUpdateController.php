<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\StudentModel;

class DataUpdateController extends BaseController
{
    public function updateStudentDataInDetectedCaseTable()
    {
        $case_model = new CaseModel();
        $students_ids = $case_model->fetchIncompleteDetailStudentIds();
        $student_model = new StudentModel();
        $count = 0;
        foreach ($students_ids as $students_id) {
            $student_details = $student_model->fetchStudentDetails($students_id['student_id']);
            if ($student_details !== null) {
                $case_ids = $case_model->getCaseIds($students_id);
                $case_model->updateDetectedStudentDetails($student_details, $case_ids);
                $count++;
            }
        }
        log_message("info", "Total students updated: $count");
    }

    public function presentDate()
    {

        $case_model = new CaseModel();
        $case_ids = $case_model->getCaseIdsForDetectingPresentDateAfterDetection();
        $attendance_model = new AttendanceModel();

        foreach ($case_ids as $case_id) {
            $first_present_date = $attendance_model->getPresentMarkedDateAfter($case_id['day'], $case_id['student_id']);
            if (!empty($first_present_date))
                $case_model->updateFirstPresentDateAfterDetection($first_present_date, $case_id['case_id']);
        }

    }

    public function updateSmsStatusReportForDetectedCases()
    {
        helper("cdac");
        $case_model = new CaseModel();
        $message_ids = $case_model->getMessageIdsWhereStatusIsNotDelivered();
        if (!empty($message_ids)) {
            $delivery_report = fetch_sms_delivery_report($message_ids[0]['message_id']);
            if (!empty($delivery_report)) {
                //@Todo: update delivery status in detected_case table;
            } else {
                log_message("notice", "Delivery report not fetched for case id: " . $message_ids[0]['case_id']);
            }
        }
    }
}

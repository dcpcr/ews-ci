<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\StudentModel;

class DataUpdateController extends BaseController
{
    private function updateStudentDataInDetectedCaseTable()
    {
        if (getenv('cron.data.updateStudentDataInDetectedCaseTable') == "0") {
            log_message("info", "updateStudentDataInDetectedCaseTable is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $students_ids = $case_model->fetchIncompleteDetailStudentIds();
        log_message("info", "Total number of student record needs to be updated: " . count($students_ids));
        if (!empty($students_ids)) {
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
            log_message("info", "Total number of students updated: $count");
        }

    }

    private function presentDateAfterDetection()
    {
        if (getenv('cron.data.presentDateAfterDetection') == "0") {
            log_message("info", "presentDateAfterDetection is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $case_ids = $case_model->getCaseIdsForDetectingPresentDateAfterDetection();
        $attendance_model = new AttendanceModel();
        if (!empty($case_ids)) {
            foreach ($case_ids as $case_id) {
                $first_present_date = $attendance_model->getPresentMarkedDateAfter($case_id['day'], $case_id['student_id']);
                if (!empty($first_present_date))
                    $case_model->updateFirstPresentDateAfterDetection($first_present_date, $case_id['case_id']);
            }

        } else {
            log_message("notice", "Zero cases for updating the latest present date after detection.");
        }

    }

    /**
     * @throws \ReflectionException
     */
    private function updateSmsStatusReportForDetectedCases()
    {
        if (getenv('cron.data.updateSmsStatusReportForDetectedCases') == "0") {
            log_message("info", "presentDateAfterDetection is not enabled. Skipping it");
            return;
        }
        helper("cdac");
        $case_model = new CaseModel();
        $message_ids = $case_model->getMessageIdsWhereStatusIsNotDelivered();
        if (!empty($message_ids)) {
            foreach ($message_ids as $message_id) {
                $delivery_report = fetch_sms_delivery_report($message_id['message_id']);
                if (!empty($delivery_report)) {
                    if($delivery_report=="Kindly try to generate Report within 8pm to 8am")
                    {
                        log_message("notice","Kindly try to generate SMS Report within 8pm to 8am");
                        break;
                    }
                    $line = preg_split("/((\r?\n)|(\r\n?))/", $delivery_report);
                    $line_arr = explode(',', $line[0]);
                    $cdac_report_data = array(
                        'id' => $message_id['case_id'],
                        'sms_delivery_status' => $line_arr[1]
                    );
                    $res = $case_model->update($message_id['case_id'], $cdac_report_data);
                    if ($res) {
                        $result = $case_model->updateReportFetchedValue($message_id['case_id']);
                        if($result)
                        {
                            log_message("info", "sms report fetched value updated for case id: " . $message_id['case_id']);
                        }
                        else{
                            log_message("error", "sms report fetched value not updated for case id: " . $message_id['case_id']);
                        }
                        log_message("notice", "Delivery report updated for case id: " . $message_id['case_id']);
                    } else {
                        log_message("notice", "Delivery report not updated for case id: " . $message_id['case_id']);
                    }
                } else {
                    log_message("notice", "Delivery report not fetched for case id: " . $message_id['case_id']);
                }
            }

        }
    }

    public function smsReport()
    {

        $this->runDailyDataUpdate(true);

    }

    public function syncData()
    {
        $this->runDailyDataUpdate();
    }

    /**
     * @throws \ReflectionException
     */
    private function runDailyDataUpdate($night = false)
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Data Update Cron request");
            $start_time = microtime(true);
            //Call Update Data functions
            if ($night) {
                $this->updateSmsStatusReportForDetectedCases();
            } else {
                //$this->updateStudentDataInDetectedCaseTable();
                $this->presentDateAfterDetection();
            }
            //Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time of script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}

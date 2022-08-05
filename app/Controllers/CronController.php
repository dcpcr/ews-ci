<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\BackToSchoolModel;
use App\Models\CallDispositionModel;
use App\Models\CaseModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\HighRiskModel;
use App\Models\HomeVisitModel;
use App\Models\ReasonForAbsenteeismModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\SmsBatchModel;
use App\Models\SmsDeliveryReportModel;
use App\Models\StudentModel;

class CronController extends BaseController
{

    protected function import_school_data()
    {
        $file_name = "schools.csv";
        $school_model = new SchoolModel();
        $school_model->updateSchools($file_name);
        $school_mapping_model = new SchoolMappingModel();
        $school_mapping_model->updateMappings();
    }

    protected function import_student_data()
    {
        $file_name = "student.csv";
        $student_model = new StudentModel();
        $student_model->updateStudents($file_name);
    }

    protected function import_attendance_data($from_date, $to_date)
    {
        $file_name = "attendance.csv";
        $attendance_model = new AttendanceModel();
        $attendance_model->downloadAttendance($file_name, $from_date, $to_date);
    }

    protected function update_detected_cases($from_date, $to_date)
    {
        $case_model = new CaseModel();
        $case_model->detectCases($from_date, $to_date);
    }

    /**
     * @throws \ReflectionException
     */
    private function updateCaseData()
    {
        helper('cyfuture');
        $cases = download_operator_form_data();
        $reason_for_absenteeism_model = new ReasonForAbsenteeismModel();
        $reason_for_absenteeism_model->insertUpdateCaseReason($cases);
        $call_disposition_model = new CallDispositionModel();
        $call_disposition_model->insertUpdateCallDisposition($cases);
        $high_risk_model = new HighRiskModel();
        $high_risk_model->insertUpdateHighRisk($cases);
        $back_to_school = new BackToSchoolModel();
        $back_to_school->insertUpdateBackToSchool($cases);
        $home_visit = new HomeVisitModel();
        $home_visit->insertUpdateHomeVisit($cases);
        $dcpcr_ticket = new DcpcrHelplineTicketModel;
        $dcpcr_ticket->insertUpdateDcpcrTicketDetails($cases);

    }

    /**
     * @throws \ReflectionException
     */
    private function sendSmsToStudentNewRecord()
    {
        helper('cdac');
        $student_model = new StudentModel();
        $mobile_numbers = $student_model->getNewStudentMobileNumbers();
        if (count($mobile_numbers) > 0) {
            $final_mobile_number_string = convert_mobile_array_to_comma_separated_string($mobile_numbers);
            send_bulk_unicode_promotional_sms($final_mobile_number_string);
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function getSmsDeliveryReport()
    {
        helper('cdac');
        $sms_batch_model = new SmsBatchModel();

        $messageIds = $sms_batch_model->getMessageId();
        for ($i = 0; $i < count($messageIds); $i++) {
            $messageId = $messageIds[$i]['message_id'];
            $batch_id = $messageIds[$i]['id'];
            fetch_sms_delivery_report($messageId, $batch_id);
            $sms_batch_model->updateReportFetchFalg($batch_id);
            sleep(10);
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function send_sms_to_all_student()
    {
        helper('cdac');
        $limit = 10000;//Sms batch of 10,000
        $offset = 0;
        $count = 0;
        $student_model = new StudentModel();
        $total_student_count = $student_model->getTotalStudentCount();
        while ($count < $total_student_count) {
            if ($offset == 0) {
                $student_mobile = $student_model->getNewStudentMobileNumbers("$limit", "$offset");
                $offset++;
                $offset = $offset + $limit;
                $final_mobile_number_string = convert_mobile_array_to_comma_separated_string($student_mobile);
                send_bulk_unicode_promotional_sms($final_mobile_number_string);
            }
            $student_mobile = $student_model->getNewStudentMobileNumbers("$limit", "$offset");
            $final_mobile_number_string = convert_mobile_array_to_comma_separated_string($student_mobile);
            send_bulk_unicode_promotional_sms($final_mobile_number_string);
            $offset = $offset + $limit;
            $count = $count + $limit;
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function smsTest($mobile_number='8882223317')
    {
        helper('cdac');
        send_single_unicode_promotional_sms("$mobile_number");
        $sms_batch_model = new SmsDeliveryReportModel();
        echo $res=$sms_batch_model->fetchLatestSmsDeliveryReportOfMobileNumbers('8882223317');
    }

    /**
     * @throws \ReflectionException
     */
    public function runDaily()
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $begin = new \DateTimeImmutable();
            $end = $begin;
            $this->updateCaseData();
            $this->sendSmsToStudentNewRecord();
            $this->getSmsDeliveryReport();
            $this->import_school_data();
            $this->import_student_data();
            $this->import_attendance_data($begin, $end);
            $this->update_detected_cases($begin, $end);
            // Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time of script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function promotionalSmsToAllStudentsCron() //Please be carefull because it will send 18 lakh sms in single call
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $this->send_sms_to_all_student();
            // Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time for send promotional sms script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}
<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\CaseReasonModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\SmsModel;
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

    protected function update_detected_case_operator_form_data()
    {
        $case_reason_model = new CaseReasonModel();
        $case_reason_model->downloadOperatorFormData();
    }

    protected function bulkSms()
    {
        $username = getenv('cdac_username');
        $password = getenv('cdac_password');
        $sender_id = getenv('cdac_senderId');
        $Secure_key = getenv('cdac_deptSecureKey');
        $template_id = "1307162126864296262";
        //Don't change the Uncode Message content.
        $messageUnicode = "बच्चे को सेहत, पोषण, या कोई और समस्या हो या संबंधित जानकारी चाहिए, तो DCPCR दिल्ली सरकार हेल्पलाइन 9311551393 पर कॉल करें।DCPCR"; //message content in unicode
        $student_modal=
        $mobile_nos = "8882223317,8318735079,9320060499";
        $sms_model = new SmsModel();
        $sms_model->sendSms($username, $password, $sender_id, $messageUnicode, $mobile_nos, $Secure_key, $template_id);
    }

    public function index()
    {

    }

    public function runDaily()
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $begin = new \DateTimeImmutable();
            $end = $begin;
            $this->import_school_data();
            $this->import_student_data();
            $this->import_attendance_data($begin, $end);
            $this->update_detected_cases($begin, $end);
            $this->update_detected_case_operator_form_data();
            // Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time of script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}
<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\BackToSchoolModel;
use App\Models\CallDispositionModel;
use App\Models\CaseModel;
use App\Models\CdacSmsModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\HighRiskModel;
use App\Models\HomeVisitModel;
use App\Models\ReasonForAbsenteeismModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\StudentModel;

class CronController extends BaseController
{

    protected function importSchoolData()
    {
        $file_name = "schools.csv";
        $school_model = new SchoolModel();
        $school_model->updateSchools($file_name);
        $school_mapping_model = new SchoolMappingModel();
        $school_mapping_model->updateMappings();
    }

    protected function importStudentData()
    {
        $file_name = "student.csv";
        $student_model = new StudentModel();
        $student_model->updateStudents($file_name);
    }

    protected function importAttendanceData($from_date, $to_date)
    {
        $file_name = "attendance.csv";
        $attendance_model = new AttendanceModel();
        $attendance_model->downloadAttendance($file_name, $from_date, $to_date);
    }

    /**
     * @throws \Exception
     */
    protected function updateDetectedCases($from_date, $to_date)
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
    private function sendSmsToAllNewStudents()
    {
        $student_model = new StudentModel();
        $student_model->send_sms_to_all_new_students(1000);
    }

    /**
     * @throws \ReflectionException
     */
    private function fetchAndUpdateSmsDeliveryReport()
    {
        $cdac_sms_model = new CdacSmsModel();
        $cdac_sms_model->downloadSmsDeliveryReport();
        $student_model = new StudentModel();
        $student_model->updateSmsDeliveryStatusOfStudentsMobileNumbers();
    }

    /**
     * @throws \ReflectionException
     */
    public function runDailyAtNight()
    {
        $this->runDaily(false);
    }

    /**
     * @throws \ReflectionException
     */
    public function runDailyAtMorning()
    {
        $this->runDaily(true);
    }


    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function runDaily($morning)
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $begin = new \DateTimeImmutable();
            $end = $begin;
            if ($morning) {
                $this->sendSmsToAllNewStudents();
            } else {
                $this->updateCaseData();
                $this->fetchAndUpdateSmsDeliveryReport();
                $this->importSchoolData();
                $this->importStudentData();
                $this->importAttendanceData($begin, $end);
                $this->updateDetectedCases($begin, $end);
            }
            // Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time of script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}
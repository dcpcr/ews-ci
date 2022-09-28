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
use App\Models\MobileSmsStatusModel;
use App\Models\ReasonForAbsenteeismModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\StudentModel;
use DateTimeImmutable;
use Exception;
use ReflectionException;

class CronController extends BaseController
{

    protected function importSchoolData()
    {
        if (getenv('cron.schooldata') == "0") {
            log_message("info", "importSchoolData is not enabled. Skipping it");
            return;
        }
        $file_name = "schools.csv";
        $school_model = new SchoolModel();
        $school_model->updateSchools($file_name);
        $school_mapping_model = new SchoolMappingModel();
        $school_mapping_model->updateMappings();
    }

    /**
     * @throws ReflectionException
     */
    protected function importStudentData()
    {
        if (getenv('cron.studentdata') == "0") {
            log_message("info", "importStudentData is not enabled. Skipping it");
            return;
        }
        $file_name = "student.csv";
        $student_model = new StudentModel();
        $student_model->updateStudents($file_name);
        $mobile_sms_status_model = new MobileSmsStatusModel();
        $mobile_sms_status_model->updateMobiles();
    }

    protected function importAttendanceData($from_date, $to_date)
    {
        if (getenv('cron.attendancedata') == "0") {
            log_message("info", "importAttendanceData is not enabled. Skipping it");
            return;
        }
        $file_name = "attendance.csv";
        $attendance_model = new AttendanceModel();
        $attendance_model->downloadAttendance($file_name, $from_date, $to_date);
    }

    /**
     * @throws Exception
     */
    public function detect($function_no, $date)
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "in detect, function value = " . $function_no);
            $case_model = new CaseModel();
            $case_model->detectCases(new DateTimeImmutable($date), $function_no);
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }

    /**
     * @throws Exception
     */
    protected function updateDetectedCases($from_date, $to_date)
    {
        if (getenv('cron.detectedcases') == "0") {
            log_message("info", "updateDetectedCases is not enabled. Skipping it");
            return;
        }

        function isRunning($pid): bool
        {
            try {
                $result = shell_exec(sprintf("ps %d", $pid));
                if (count(preg_split("/\n/", $result)) > 2) {
                    return true;
                }
            } catch (Exception $e) {
            }
            return false;
        }

        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            exec("cd " . FCPATH);
            $pids = [];
            for ($i = 1; $i <= 4; $i++) {
                $pids [] = exec(
                    "php index.php cron detect"
                    . " " . $i
                    . " " . $date->format('Y-m-d')
                    . " > /dev/null 2>&1 & echo $!; ", $result
                );
            }
            foreach ($pids as $pid) {
                while (isRunning($pid)) {
                    sleep(10);
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    private function updateCaseData()
    {
        if (getenv('cron.casedata') == "0") {
            log_message("info", "updateCaseData is not enabled. Skipping it");
            return;
        }
        helper('cyfuture');
        download_and_save_operator_form_data();
        download_and_save_operator_form_data(true);
        $dcpcr_helpline_ticket_model = new DcpcrHelplineTicketModel();
        $dcpcr_helpline_ticket_model->updateDcpcrTicketDetails();
    }

    /**
     * @throws ReflectionException
     */
    private function sendSms()
    {
        if (getenv('cron.sms') == "0") {
            log_message("info", "sendSms is not enabled. Skipping it");
            return;
        }
        $mobile_model = new MobileSmsStatusModel();
        $mobile_model->sendSmsToAllNewNumbers(10000);
    }

    /**
     * @throws ReflectionException
     */
    private function fetchAndUpdateSmsDeliveryReport()
    {
        if (getenv('cron.smsdeliveryreport') == "0") {
            log_message("info", "fetchAndUpdateSmsDeliveryReport is not enabled. Skipping it");
            return;
        }
        $cdac_sms_model = new CdacSmsModel();
        $cdac_sms_model->downloadSmsDeliveryReport();
    }

    /**
     * @throws ReflectionException
     */
    private function sendCronStatusInfoSms($date)
    {
        if (getenv('cron.sendreport') == "0") {
            log_message("info", "sendCronStatusInfoSms is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $response_data = $case_model->getCaseReport($date);
        helper("ews_sms_template");
        ews_daily_report_sms($response_data, $date);
    }

    /**
     * @throws ReflectionException
     */
    private function sendCronErrorSms($morning)
    {
        if (getenv('cron.sendcronalert') == "0") {
            log_message("info", "sendCronErrorSms is not enabled. Skipping it");
            return;
        }
        helper("ews_sms_template");
        ews_cron_error_sms($morning);
    }


    /**
     * @throws ReflectionException
     */
    public function runDailyAtNight()
    {
        $this->runDaily(false);
    }

    /**
     * @throws ReflectionException
     */
    public function runDailyAtMorning()
    {
        $this->runDaily(true);
    }


    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function runDaily($morning)
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $begin = new DateTimeImmutable();
            $end = $begin;
            try {
                if ($morning) {
                    $this->sendSms();
                } else {
                    $this->updateCaseData();
                    $this->fetchAndUpdateSmsDeliveryReport();
                    $this->importSchoolData();
                    $this->importStudentData();
                    $this->importAttendanceData($begin, $end);
                    $this->updateDetectedCases($begin, $end);
                    $this->sendCronStatusInfoSms($begin);
                }
                // Calculate script execution time
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time);
                log_message('info', "Execution time of script = " . $execution_time . " sec");
            } catch (Exception $e) {
                log_message('error', "Cron failed to finish. Error - " . $e->getMessage());
                $this->sendCronErrorSms($morning);
            }
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}
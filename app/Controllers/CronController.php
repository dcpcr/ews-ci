<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\AttendanceReportModel;
use App\Models\CaseModel;
use App\Models\CdacSmsModel;
use App\Models\DcpcrHelplineTicketModel;
use App\Models\HomeVisitModel;
use App\Models\MobileSmsStatusModel;
use App\Models\NameStruckOffModel;
use App\Models\SchoolLeavingCertificateModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\StudentModel;
use App\Models\YetToBeContactedModel;
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

    private function importStudentDataInFile()
    {
        if (getenv('cron.studentdatainfile') == "0") {
            log_message("info", "importStudentDatainfile is not enabled. Skipping it");
            return;
        }
        $student_model = new StudentModel();
        $student_model->fetchStudents();

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
     * @throws ReflectionException
     */
    public function attendanceReport($from_date, $to_date)
    {
        if (getenv('cron.attendancereport') == "0") {
            log_message("info", "Prepare daily attendance report is not enabled. Skipping it");
            return;
        }
        $file_name = 'attendance_report.csv';
        $attendance_model = new AttendanceReportModel();
        $attendance_model->createClassWiseDailyAttendanceReport($file_name, $from_date, $to_date);
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
    private function updateCaseData($begin, $end)
    {
        if (getenv('cron.updatecasedata') == "0") {
            log_message("info", "updateCaseData is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $case_model->updateOperatorFormData($begin, $end);
    }

    /**
     * @throws ReflectionException
     */
    private function homeVisitCases($begin, $end)
    {
        if (getenv('cron.homevisitcases') == "0") {
            log_message("info", "homeVisitCases is not enabled. Skipping it");
            return;
        }
        $home_visit_model = new HomeVisitModel();
        $home_visit_model->updateHomeVisitData($begin, $end);

    }

    /**
     * @throws ReflectionException
     */
    private function yetToBeTakenUpCases($end)
    {
        if (getenv('cron.yettobetakenupcases') == "0") {
            log_message("info", "yetToBeTakenUpCases is not enabled. Skipping it");
            return;
        }
        $home_visit_model = new YetToBeContactedModel();
        $home_visit_model->updateYetToBeTakenUpData($end);

    }

    /**
     * @throws ReflectionException
     */
    private function fetchTickets($begin, $end)
    {
        if (getenv('cron.fetch_ticket_number') == "0") {
            log_message("info", "fetchTicketNumber is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $case_model->downloadAndSaveTicketDetails($begin, $end);
    }

    /**
     * @throws ReflectionException
     */
    private function updateTicketDetails()
    {
        if (getenv('cron.update_ticket_details') == "0") {
            log_message("info", "updateTicketData is not enabled. Skipping it");
            return;
        }
        $dcpcr_helpline_ticket_model = new DcpcrHelplineTicketModel();
        $dcpcr_helpline_ticket_model->updateOpenTicketFromNsbbpo();
    }

    public function updateBackToSchool($from_date, $to_date)
    {
        if (getenv('cron.backtoschool') == "0") {
            log_message("info", "updateBackToSchool is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $case_model->detectAndMarkBackToSchoolCases($from_date, $to_date);
    }

    /**
     * @throws ReflectionException
     */
    private function sendSms()
    {
        if (getenv('cron.send_sms') == "0") {
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
        if (getenv('cron.send_cron_status_info') == "0") {
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
    public function updateLatestStudentStatus()
    {
        if (getenv('cron.updateLatestStudentStatus') == "0") {
            log_message("info", "updateLatestStudentStatus is not enabled. Skipping it");
            return;
        }
        $case_model = new CaseModel();
        $case_model->updateDetectedStudentStatus();

    }

    /**
     * @throws ReflectionException
     */
    public function updateTotalStudentCount()
    {
        if (getenv('cron.updateTotalStudentCount') == "0") {
            log_message("info", "updateTotalStudentCount is not enabled. Skipping it");
            return;
        }
        $case_model = new StudentModel();
        $case_model->prepareSchoolWiseTotalStudents();

    }

    /**
     * @throws ReflectionException
     */
    private function updateNameStruckOff()
    {
        if (getenv('cron.update_name_struck_off') == "0") {
            log_message("info", "updateNameStruckOff is not enabled. Skipping it");
            return;
        }
        $nso_model = new NameStruckOffModel();
        $nso_model->fetchNameStruckOffData();


    }

    private function updateSchoolLeavingCertificate()
    {
        if (getenv('cron.school_leaving_certificate') == "0") {
            log_message("info", "updateSchoolLeavingCertificate is not enabled. Skipping it");
            return;
        }
        $slc_model = new SchoolLeavingCertificateModel();
        $slc_model->fetchSchoolLeavingCertificateData();

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
            if (getenv('cron.run_cron_from_previous_date') == "1") {
                $begin = new DateTimeImmutable(getenv("cron.from_date"));
                $end = new DateTimeImmutable(getenv("cron.to_date"));
                log_message("info", "The cron is running from date: " . $begin->format('Y-m-d') . " to date: " . $end->format('Y-m-d'));
            } else {
                $begin = new DateTimeImmutable();
                $end = $begin;
            }
            try {
                if ($morning) {
                    $this->sendSms();
                } else {
                    $this->fetchAndUpdateSmsDeliveryReport();
                    $this->importSchoolData();
                    $this->importStudentDataInFile();
                    $this->importStudentData();
                    $this->importAttendanceData($begin, $end);
                    $this->attendanceReport($begin, $end);
                    $this->updateDetectedCases($begin, $end);
                    $this->updateLatestStudentStatus();
                    $this->updateTotalStudentCount();
                    $this->updateCaseData($begin, $end);
                    $this->homeVisitCases($begin, $end);
                    $this->yetToBeTakenUpCases($end);
                    $this->fetchTickets($begin, $end);
                    $this->updateTicketDetails();
                    $this->updateBackToSchool($begin, $end);
                    $this->updateNameStruckOff();
                    $this->updateSchoolLeavingCertificate();
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
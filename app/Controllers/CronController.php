<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
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

    public function runDaily()
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            //$begin = new \DateTimeImmutable();
            //$end = $begin;
            $begin = new \DateTimeImmutable('2022-07-09');
            $end = new \DateTimeImmutable('2022-07-12');
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
}
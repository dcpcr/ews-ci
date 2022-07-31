<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\CaseModel;
use App\Models\ClassModel;
use App\Models\SchoolAttendanceModel;
use App\Models\SchoolMappingModel;
use App\Models\SchoolModel;
use App\Models\StudentModel;
use Exception;

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

    /**
     * @throws \ReflectionException
     */
    protected function import_student_data()
    {
        //$file_name = "student.csv";
        //$student_model = new StudentModel();
        //$student_model->updateStudents($file_name);
        $class_model = new ClassModel();
        $class_model->updateClasses();
    }

    /**
     * @throws \ReflectionException
     */
    protected function import_attendance_data($from_date, $to_date)
    {
        //$file_name = "attendance.csv";
        //$attendance_model = new AttendanceModel();
        //$attendance_model->downloadAttendance($file_name, $from_date, $to_date);
        $school_attendance = new SchoolAttendanceModel();
        $school_attendance->updateSchoolAttendance($from_date, $to_date);
    }

    protected function update_detected_cases($from_date, $to_date)
    {
        $case_model = new CaseModel();
        $case_model->detectCases($from_date, $to_date);
    }

    /**
     * @throws Exception
     */
    protected function fetch_attendance()
    {
        //$file_name = "detected_cases.csv";
        $attendance_model = new AttendanceModel();
        //$attendance_model->getStudentAttendance($file_name, "detected_attendance.csv");
        $attendance_model->getStudentAttendance("detected_attendance.csv");
    }

    /**
     * @throws Exception
     */
    public function runDaily()
    {
        ini_set("memory_limit", "-1");
        if ($this->request->isCLI()) {
            log_message('info', "Cron request");
            $start_time = microtime(true); //Find a better mechanism of logging time of execution
            $begin = new \DateTimeImmutable('2022-04-01');
            $end = new \DateTimeImmutable('2022-07-29');
            //$this->import_school_data();
            //$this->import_student_data();
            $this->import_attendance_data($begin, $end);
            //$this->update_detected_cases($begin, $end);
            // Calculate script execution time
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            log_message('info', "Execution time of script = " . $execution_time . " sec");
        } else {
            log_message('info', "Access to this functionally without CLI is not allowed");
        }
    }
}
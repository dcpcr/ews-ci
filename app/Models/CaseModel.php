<?php

namespace App\Models;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detected_case';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['student_id', 'detection_criteria', 'day'];


    public function detectedCases($from_date, $to_date)
    {
        $attendance_model = new AttendanceModel();
        for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
            $count = 0;
            $data_array = array();
            $marked_students = $attendance_model->distinct()->select('student_id')
                ->where("date ='" . $date->format("d/m/Y") . "'")->findAll();
            if (empty($marked_students)) {
                log_message("info", "No students' attendance is marked for the day " . $date->format("d/m/Y"));
            }
            $open_cases = $this->distinct()->select('student_id')->where("status != 'Back To School'")
                ->orderBy("student_id")->findAll();
            foreach ($marked_students as $student) {
                $student_id = $student['student_id'];
                if (!in_array("$student_id", array_column($open_cases, 'student_id'), true)) {
                    $student_attendance = $attendance_model->getStudentAttendanceForLast30DaysFrom($student_id, $date);
                    $continuous_absent_count = 0;
                    $absent_count = 0;
                    $flag_seven = false;
                    $detection_criteria = "";
                    $detected = false;
                    foreach ($student_attendance as $row1) {
                        $attendance_status = $row1['attendance_status'];
                        if ($attendance_status == 'a') {
                            $absent_count++;
                            if (!$flag_seven) {
                                $continuous_absent_count++;
                            }
                        } else {
                            $flag_seven = true;
                        }
                        if ($absent_count >= 20) {
                            $detection_criteria = "<60% Attendance";
                            $detected = true;
                            break;
                        }
                        if ($continuous_absent_count >= 7) {
                            $detection_criteria = "7 Consecutive Days Absent";
                            $detected = true;
                            break;
                        }
                    }
                    if ($detected) {
                        $day = $date->format("Y/m/d");
                        $data = [
                            'student_id' => $student_id,
                            'detection_criteria' => "$detection_criteria",
                            'day' => "$day",
                        ];
                        $data_array [] = $data;
                        $count++;
                    }
                }
            }
            if (count($data_array) > 0) {
                try {
                    $this->insertBatch($data_array);
                } catch (\ReflectionException $e) {
                    //TODO: Log message
                }
            }
            log_message('info', $count . " new cases detected for date - " . $date->format("d/m/Y"));
        }
    }
}

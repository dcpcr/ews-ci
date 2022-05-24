<?php

namespace App\Models;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detected_case';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';

    public function detectedCases($from_date, $to_date)
    {

        $open_cases = $this->distinct()->select('student_id')->where("status != 'Back To School'")->findAll();
        for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
            //$cases_sql = "INSERT INTO detected_case(student_id, detection_criteria, day) VALUES";
            $count = 0;
            //$sql = "SELECT distinct(student_id) FROM attendance where date = '" . $date->format("d/m/Y") . "'";
            $attendance_model = new AttendanceModel();
            $marked_students = $attendance_model->distinct()->select('student_id')
                ->where("date ='" . $date->format("d/m/Y") . "'")->findAll();
            if (empty($marked_students)) {
                log_message("info", "No students' attendance is marked for the day " . $date->format("d/m/Y"));
            }
            foreach ($marked_students as $student_id) {
                if (in_array($student_id, $open_cases)) {
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
                        //$cases_sql .= "($student_id, '$detection_criteria', '$day'),";
                        $data = [
                            'student_id' => $student_id,
                            'detection_criteria'    => "$detection_criteria",
                            'day' => "day",
                        ];
                        try {
                            $this->insert($data);
                        } catch (\ReflectionException $e) {
                            //TODO: Log message
                        }
                        $count++;
                    }
                }
            }
/*            if ($count > 0) {
                $cases_sql[strlen($cases_sql) - 1] = ';';
                if (!(mysqli_query($conn, $cases_sql))) {
                    echo "\nQuery execute failed: error - " . mysqli_error($conn);
                }
            }*/
            echo $date->format('d/m/Y') . "- $count\r\n";
            //echo $cases_sql."\r\n";
        }
    }
}

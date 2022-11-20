<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use ReflectionException;

class AttendanceReportModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'attendance_report';
    protected $primaryKey = 'school_id,class,date';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [];


    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('ews');
    }

    /**
     * @throws ReflectionException
     */
    public function createClassWiseDailyAttendanceReport($file_name, $from_date, $to_date): void
    {
        $student_model = new StudentModel();
        $school_list = get_school_ids();
        $attendance_model = new AttendanceModel();
        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            foreach ($school_list as $school) {
                $class_wise_student_count = $student_model->getClassWiseStudentCount($school);
                if (!empty($class_wise_student_count)) {
                    log_message("info", "Total " . count($class_wise_student_count) .
                        " classes' student-count fetched for school id: " . $school['id'] . " date: " . $date->format("Y-m-d"));
                    $attendance_report = [];
                    foreach ($class_wise_student_count as $row) {
                        $attendance_report[$row['school_id']][$row['class']] = [
                            "date" => $date->format("Y-m-d"),
                            "school" => $row['school_id'],
                            "class" => $row['class'],
                            "total_student" => $row['count'],
                            "total_present" => 0,
                            "total_absent" => 0,
                            "total_leave" => 0,
                        ];
                    }
                    $attendance_status_count = $attendance_model->getDailyAttendanceReportForSchool($school['id'], $date);
                    if (!empty($attendance_status_count)) {
                        foreach ($attendance_status_count as $row) {
                            $attendance_report[$row['school_id']][$row['class']] = [
                                "date" => $date->format("Y-m-d"),
                                "school" => $school['id'],
                                "class" => $row['class'],
                                "total_student" => $attendance_report[$row['school_id']][$row['class']]["total_student"],
                                "total_present" => $row['present_count'],
                                "total_absent" => $row['absent_count'],
                                "total_leave" => $row['leave_count'],
                            ];
                        }
                    }
                    dump_array_in_file($attendance_report[$school['id']], $file_name, false);
                    import_data_into_db("$file_name", $this->DBGroup, $this->table);
                    log_message("info", "attendance report imported for school_id: " . $school['id'] . " date: " . $date->format("Y-m-d"));
                } else {
                    log_message("info", "no class wise data for school id: " . $school['id'] . " date: " . $date->format("Y-m-d"));
                }
            }
        }
    }
}

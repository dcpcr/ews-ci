<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use DateTimeImmutable;
use Exception;

class AttendanceModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'attendance';
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = [];

    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('ews');
        helper('general');
        helper('edutel');
    }

    public function downloadAttendance(string $file_name, \DateTimeInterface $from_date, \DateTimeInterface $to_date)
    {
        $school_ids = get_school_ids();
        $count = 0;
        $exists = false;
        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            $total_attendance_count = 0;
            foreach ($school_ids as $school_id) {
                $id = $school_id['id'];
                $final_array = array();
                $school_attendance_count = 0;
                $data_array = fetch_attendance_from_edutel($date->format("d/m/Y"), $id);
                if ($data_array) {
                    for ($i = 0; $i < count($data_array); $i++) {
                        if (trim($data_array[$i]['attendance']) != "") {
                            $data_array[$i]['date'] = $date->format("d/m/Y");
                            $final_array[$i]['student_id'] = $data_array[$i]['Student_ID'];
                            $final_array[$i]['attendance'] = $data_array[$i]['attendance'];
                            $final_array[$i]['date'] = $data_array[$i]['date'];
                            $school_attendance_count++;
                        }
                    }
                    if ($count != 0)
                        $exists = true;
                    dump_array_in_file($final_array, $file_name, $exists);
                    log_message("info", $school_attendance_count . " attendance fetched for school id - " .
                        $id . " date - " . $date->format('d/m/Y'));
                } else {
                    log_message("info", "No attendance fetched for school id - " . $id . " date - " .
                        $date->format('d/m/Y'));
                }
                $count++;
                $total_attendance_count += count($final_array);
            }
            log_message("info", $total_attendance_count . " attendance records fetched for date = " . $date->format("d/m/Y"));
        }
        import_data_into_db($file_name, $this->DBGroup, $this->table);
        log_message("info", "Attendance records dumped in DB. " . $date->format("d/m/Y"));
    }

    public function getStudentAttendanceForLastNDaysFrom($student_id, $date, $N): array
    {
        return $this->select(["STR_TO_DATE(date,'%d/%m/%Y') as day", "attendance_status"])
            ->where("student_id = '$student_id' and 
            STR_TO_DATE(date,'%d/%m/%Y') <= STR_TO_DATE('" . $date->format("d/m/Y") . "','%d/%m/%Y')")
            ->orderBy("day", "desc")->findAll($N);
    }

    public function getStudentAttendanceBetween($student_id, $from_date, $to_date): array
    {
        return $this->select(["STR_TO_DATE(date,'%d/%m/%Y') as day", "attendance_status"])
            ->where("student_id = '$student_id' and 
            STR_TO_DATE(date,'%d/%m/%Y') <= STR_TO_DATE('" . $to_date->format("d/m/Y") . "','%d/%m/%Y') and 
            STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('" . $from_date->format("d/m/Y") . "','%d/%m/%Y')")
            ->orderBy("day", "desc")->findAll();
    }

    /**
     * @throws Exception
     */
    public function getStudentAttendanceForCases($case_ids): array
    {
        $case_model = new CaseModel();
        $fetched_cases = $case_model->getCasesForIds($case_ids);
        $data_array = array();
        $i = 0;
        foreach ($fetched_cases as $case) {
            $data_array[$i]['id'] = $case['id'];
            $data_array[$i]['student_id'] = $case['student_id'];
            $today = new DateTimeImmutable();
            $case_day = new DateTimeImmutable($case['day']);
            $attendance = $this->getStudentAttendanceBetween($case['student_id'], $case_day, $today);
            $j = 0;
            foreach ($attendance as $att) {
                $j++;
                $data_array[$i]['attendance' . $j] = $att['day'] . "-" . $att['attendance_status'];
            }
            $i++;
        }
        return $data_array;
    }

    /**
     * @throws Exception
     */
    public function getStudentAttendance($filename, $tofilename)
    {
        $cases = get_array_from_csv($filename);
        $data_array = $this->getStudentAttendanceForCases(array_column($cases, 0));
        dump_array_in_file($data_array, $tofilename, false);
    }
}

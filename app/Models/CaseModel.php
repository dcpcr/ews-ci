<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTimeImmutable;
use Exception;
use SSP\SSP;

class CaseModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detected_case';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['student_id', 'day', 'seven_days_criteria', 'thirty_days_criteria', 'system_bts', 'priority'];


    /**
     * @throws Exception
     */
    public function detectCases(\DateTimeInterface $from_date, \DateTimeInterface $to_date)
    {
        $attendance_model = new AttendanceModel();
        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            $insert_count = 0;
            $update_count = 0;
            $insert_data_array = array();
            $update_data_array = array();
            $marked_students = $attendance_model->distinct()->select('student_id')
                ->where("date = STR_TO_DATE('" . $date->format("d/m/Y") . "', '%d/%m/%Y')")->findAll();
            if (empty($marked_students)) {
                log_message("info", "No students' attendance is marked for the day " . $date->format("d/m/Y"));
            }
            $open_cases = $this->distinct()->select('student_id')->where("status != 'Back To School'")
                ->orderBy("student_id")->findAll();

            foreach ($marked_students as $student) {
                $student_id = $student['student_id'];
                if (!in_array("$student_id", array_column($open_cases, 'student_id'), true)) {
                    $student_attendance = $attendance_model->getStudentAttendanceForLastNDaysFrom($student_id, $date, 30);
                    $detected = false;
                    $flag_seven = false;
                    if ($this->isDetectedSevenDays($student_attendance)) {
                        $flag_seven = true;
                        $detected = true;
                    }
                    $flag_thirty = false;
                    if ($this->isDetectedThirtyDays($student_attendance)) {
                        $flag_thirty = true;
                        $detected = true;
                    }
                    if ($detected) {
                        $day = $date->format("Y/m/d");
                        if ($flag_thirty && $flag_seven) {
                            $priority = "High";
                        } else {
                            $priority = "Medium";
                        }
                        $data = [
                            'student_id' => $student_id,
                            'seven_days_criteria' => $flag_seven ? 1 : 0,
                            'thirty_days_criteria' => $flag_thirty ? 1 : 0,
                            'priority' => "$priority",
                            'day' => "$day",
                        ];
                        $insert_data_array [] = $data;
                        $insert_count++;
                    }
                } else {
                    $case = $this->where("status != 'Back To School' AND student_id = $student_id")->first();
                    if ($case) {
                        $case_day = new DateTimeImmutable($case['day']);
                        $student_attendance = $attendance_model->getStudentAttendanceBetween($student_id, $case_day, $date);
                        $bts_count = $this->getPresentDays($student_attendance, $case_day);
                        $flag_seven = (bool)$case['seven_days_criteria'] || $this->isDetectedSevenDays($student_attendance);
                        $flag_thirty = (bool)$case['thirty_days_criteria'] || $this->isDetectedThirtyDays($student_attendance);;
                        if ($bts_count == 0) {
                            if ($flag_thirty && $flag_seven) {
                                $priority = "High";
                            } else {
                                $priority = "Medium";
                            }
                        } else {
                            $priority = "Low";
                        }
                        if ($priority != $case['priority'] || $bts_count > $case['system_bts']) {
                            $data = [
                                'id' => $case['id'],
                                'student_id' => $student_id,
                                'seven_days_criteria' => $flag_seven ? 1 : 0,
                                'thirty_days_criteria' => $flag_thirty ? 1 : 0,
                                'priority' => "$priority",
                                'system_bts' => $bts_count,
                            ];
                            $update_data_array [] = $data;
                            $update_count++;
                            log_message('info', "Priority updated for case id - " . $case['id'] .
                                " from " . $case['priority'] . " to " . $priority . " for date - " . $date->format("d/m/Y"));
                        }
                    }
                }
            }
            $insert_count = count($insert_data_array);
            if ($insert_count > 0) {
                try {
                    $this->insertBatch($insert_data_array);
                } catch (\ReflectionException $e) {
                    log_message("error", "Case Insert Failed! There were " . $insert_count . " cases detected. on date - " . $date->format("d/m/Y"));
                }
            }
            $update_count = count($update_data_array);
            if ($update_count > 0) {
                try {
                    $this->updateBatch($update_data_array, 'id');
                } catch (\ReflectionException $e) {
                    log_message("error", "Case Update Failed! There were  " . $update_count . " cases that needed update. on date - " . $date->format("d/m/Y"));
                }
            }
            $insert_count = $insert_count . " new cases detected for date - " . $date->format("d/m/Y");
            log_message('info', $insert_count);
            send_status_sms($insert_count);
            $update_count = $update_count . " cases updated on date - " . $date->format("d/m/Y");
            log_message('info', $update_count);
            send_status_sms($update_count);
        }
    }

    public function getCasesForIds($caseIds): array
    {
        return $this->whereIn('id', $caseIds)->findAll();
    }

    public function getDetectedCasesCountGenderWise(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([
            'count(*) as count',
            'student.gender as gender',
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->groupBy('gender')
            ->findAll();
    }

    public function getDetectedCasesForDataTable(array $school_ids, array $classes, $start, $end): array
    {

        helper('general');
        $master_db = get_database_name_from_db_group('master');
        $query = $this->select([
            $this->table . '.id as case_id',
            $this->table . '.seven_days_criteria',
            $this->table . '.thirty_days_criteria',
            $this->table . '.system_bts',
            $this->table . '.priority',
            $this->table . '.day',
            $this->table . '.status',
            'student.id as student_id',
            'student.name as student_name',
            'student.gender',
            'student.class',
            'student.section',
            'student.dob',
            'student.mother',
            'student.father',
            'student.mobile',
            'student.address',
            'school.id as school_id',
            'school.name as school_name',
            'school.district',
            'school.zone'
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->getCompiledSelect();

        $table = '(' . $query . ') temp';

        $columns = array(
            array('db' => 'case_id', 'dt' => 0),
            array('db' => 'seven_days_criteria', 'dt' => 1),
            array('db' => 'thirty_days_criteria', 'dt' => 2),
            array('db' => 'system_bts', 'dt' => 3),
            array('db' => 'priority', 'dt' => 4),
            array('db' => 'day', 'dt' => 5),
            array('db' => 'status', 'dt' => 6),
            array('db' => 'student_id', 'dt' => 7),
            array('db' => 'student_name', 'dt' => 8),
            array('db' => 'gender', 'dt' => 9),
            array('db' => 'class', 'dt' => 10),
            array('db' => 'section', 'dt' => 11),
            array('db' => 'dob', 'dt' => 12),
            array('db' => 'mother', 'dt' => 13),
            array('db' => 'father', 'dt' => 14),
            array('db' => 'mobile', 'dt' => 15),
            array('db' => 'address', 'dt' => 16),
            array('db' => 'school_id', 'dt' => 17),
            array('db' => 'school_name', 'dt' => 18),
            array('db' => 'district', 'dt' => 19),
            array('db' => 'zone', 'dt' => 20),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => get_database_username_from_db_group($this->DBGroup),
            'pass' => $this->db()->password,
            'db' => $this->db()->database,
            'host' => $this->db()->hostname,
        );
        return SSP::simple($_GET, $sql_details, $table, 'case_id', $columns);
    }

    public function getDetectedCaseForAPI($from_date, $to_date, $offset, $no_of_records_per_page)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([
            $this->table . '.id as case_id',
            $this->table . '.seven_days_criteria',
            $this->table . '.thirty_days_criteria',
            $this->table . '.system_bts',
            $this->table . '.priority',
            $this->table . '.day as date',
            $this->table . '.status',
            'student.id as student_id',
            'student.name as student_name',
            'student.gender',
            'student.class',
            'student.section',
            'student.dob',
            'student.mother',
            'student.father',
            'student.mobile',
            'student.address',
            'school.id as school_id',
            'school.name as school_name',
            'school.district',
            'school.zone'
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->where("day >= '" . $from_date . "' and day <='" . $to_date . "'")
            ->limit($no_of_records_per_page, $offset)
            ->find();
    }

    public function getTotalNumberOfCaseData($from_date, $to_date)
    {
        return $this->select(['id'])
            ->where("day >= '" . $from_date . "' and day <='" . $to_date . "'")
            ->countAllResults();
    }

    protected function isDetectedSevenDays(array $student_attendance): bool
    {
        $continuous_absent_count = 0;
        foreach ($student_attendance as $row1) {
            $attendance_status = $row1['attendance_status'];
            if ($attendance_status == 'a') {
                $continuous_absent_count++;
            } else {
                return false;
            }
            if ($continuous_absent_count >= 7) {
                return true;
            }
        }
        return false;
    }

    protected function isDetectedThirtyDays(array $student_attendance): bool
    {
        $absent_count = 0;
        foreach ($student_attendance as $row1) {
            $attendance_status = $row1['attendance_status'];
            if ($attendance_status == 'a') {
                $absent_count++;
            }
            if ($absent_count >= 20) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    protected function getPresentDays(array $student_attendance, $day): int
    {
        $count = 0;
        foreach ($student_attendance as $row1) {
            $attendance_status = $row1['attendance_status'];
            $attendance_day = new \DateTimeImmutable($row1['day']);
            if ($attendance_day > $day && $attendance_status == 'p') {
                $count++;
            }
        }
        return $count;
    }

    public function getStudentDetails($case_id)
    {
        helper('general');
        $res = $this->select(['student_id'])->find("$case_id");
        if ($res != NULL) {
            $student_model = new StudentModel();
            $response = $student_model->getStudentDetailsFormStudentTable($res['student_id']);
            if ($response != 'NULL') {
                return $response;
            }
        } else {
            return false;
        }
    }
}

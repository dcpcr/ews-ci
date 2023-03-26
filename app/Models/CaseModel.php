<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use ReflectionException;
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
    public function detectCases(DateTimeInterface $date, $function_no)
    {
        $attendance_model = new AttendanceModel();
        $attendance_count = $attendance_model->select('student_id')
            ->where("date = STR_TO_DATE('" . $date->format("d/m/Y") . "', '%d/%m/%Y')")
            ->countAllResults();
        $limit = ceil($attendance_count / 4);
        $offset = $limit * ($function_no - 1);
        $marked_students = $attendance_model->distinct()->select('student_id')
            ->where("date = STR_TO_DATE('" . $date->format("d/m/Y") . "', '%d/%m/%Y')")
            ->findAll($limit, $offset);
        if (empty($marked_students)) {
            log_message("info", "No students' attendance is marked for the day " . $date->format("d/m/Y"));
        }
        $open_cases = $this->distinct()->select('student_id')->where("status != 'Back To School'")
            ->orderBy("student_id")->findAll();

        list($insert_count, $update_count) = $this->detect($marked_students, $open_cases, $date);

        log_message('info', $insert_count . " new cases detected for date - " . $date->format("d/m/Y"));
        log_message('info', $update_count . " cases updated on date - " . $date->format("d/m/Y"));
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
            $this->table . '.date_of_bts',
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
            'school.zone',
            'sms.sms_status',
            'count(*) as no_time'
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->join($master_db . '.mobile_sms_status as sms', 'student.mobile = sms.mobile')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->groupBy("student_id")
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
            array('db' => 'sms_status', 'dt' => 21),
            array('db' => 'date_of_bts', 'dt' => 22),
            array('db' => 'no_time', 'dt' => 23),
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
            'school.zone',
            'sms.sms_status'
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->join($master_db . '.mobile_sms_status as sms', 'student.mobile = sms.mobile')
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
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    protected function detect(array $marked_students, array $open_cases, DateTimeInterface $date): array
    {
        $attendance_model = new AttendanceModel();
        $insert_count = 0;
        $update_count = 0;
        $insert_data_array = array();
        $update_data_array = array();
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
            } catch (ReflectionException $e) {
                log_message("error", "Case Insert Failed! There were " . $insert_count . " cases detected. on date - " . $date->format("d/m/Y"));
            }
        }
        $update_count = count($update_data_array);
        if ($update_count > 0) {
            try {
                $this->updateBatch($update_data_array, 'id');
            } catch (ReflectionException $e) {
                log_message("error", "Case Update Failed! There were  " . $update_count . " cases that needed update. on date - " . $date->format("d/m/Y"));
            }
        }
        return array($insert_count, $update_count);
    }


    public function getCaseReport(DateTimeInterface $date): array
    {
        $total_case_count = $this->selectCount('id')->where('day', $date->format("Y-m-d"))->findAll();
        $count_priority_wise = $this->select(['priority', 'count(*) as count'])
            ->where('day', $date->format("Y-m-d"))
            ->groupBy('priority')
            ->orderBy('priority', 'desc')
            ->findAll();
        if ($total_case_count[0]['id'] != 0) {
            return $data = ['Total_Case_Count' => $total_case_count, 'Priority_Wise_Count' => $count_priority_wise];
        }
        return [];
    }

    //TODO: Add more statuses which may be considered as closed cases. e.g, untraceable.
    protected function getUnclosedCasesOlderThanNDays(DateTimeInterface $date, $n): array
    {
        $response = $this->select(['id', 'student_id'])
            ->where("DATEDIFF(`day`,STR_TO_DATE('" . $date->format("d-m-Y") . "', '%d-%m-%Y'))<=", "-$n")
            ->where("status != 'Back To School'")
            ->orderBy("student_id")
            ->findAll();
        $response_count = count($response);
        log_message("info", "Total $n Days old Detected Cases: $response_count from date: " . $date->format("d-m-Y"));
        return $response;
    }

    public function detectAndMarkBackToSchoolCases(DateTimeInterface $from_date, DateTimeInterface $to_date)
    {
        for ($date = $from_date; $date <= $to_date; $date = $date->modify('+1 day')) {
            $bts_counter = 0;
            $not_bts_counter = 0;
            $potential_cases = $this->getUnclosedCasesOlderThanNDays($date, 30);
            if (!empty($potential_cases)) {
                $ticket_model = new DcpcrHelplineTicketModel();
                foreach ($potential_cases as $case) {
                    $student_id = $case['student_id'];
                    $case_id = $case['id'];
                    if ($this->isStudentPresentAtLeastNDaysInLast30Days($student_id, $date, 20) &&
                        $ticket_model->isTicketNotOpen($case_id)) {
                        $this->markStudentAsBackToSchool($case_id, $date);
                        $bts_counter++;
                    } else {
                        $not_bts_counter++;
                    }
                }
            }
            log_message("info", "Total No of Students not marked as BTS for date: " . $date->format("d/m/Y") . " is " . $not_bts_counter);
            log_message("info", "Total No of Students marked as BTS for date: " . $date->format("d/m/Y") . " is " . $bts_counter);
        }
    }

    protected function isStudentPresentInLastSevenDays($student_id, $date): bool
    {
        return $this->isStudentPresentForAtLeastNDaysInLastMDays($student_id, $date, 1, 7);
    }

    protected function isStudentPresentAtLeastNDaysInLast30Days($student_id, $date, $n): bool
    {
        return $this->isStudentPresentForAtLeastNDaysInLastMDays($student_id, $date, $n, 30);
    }

    protected function isStudentPresentForAtLeastNDaysInLastMDays($student_id, $date, $n, $m): bool
    {
        if ($n > $m) {
            return false;
        }
        $attendance_model = new AttendanceModel();
        $student_attendance = $attendance_model->getStudentAttendanceForLastNDaysFrom($student_id, $date, $m);
        $present_count = 0;
        foreach ($student_attendance as $row) {
            $attendance_status = $row['attendance_status'];
            if ($attendance_status == 'p') {
                $present_count++;
                if ($present_count >= $n) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function markStudentAsBackToSchool($case_id, $date)
    {
        $data = [
            'status' => 'Back To School',
            'date_of_bts' => $date->format("Y/m/d"),
        ];
        $this->builder->where('id', $case_id);
        $this->builder->update($data);
    }

    /**
     * @throws ReflectionException
     */
    public function downloadAndSaveTicketDetails(DateTimeInterface $from_date, DateTimeInterface $to_date)
    {
        helper('cyfuture');
        $url = get_cyfuture_helpline_ticket_url();
        $record_count = download_and_process_cyfuture_api_data(
            $url, $from_date->format("Y-m-d"),
            $to_date->format("Y-m-d"), function ($records, $page_number) use ($url) {
            if ($records) {
                $dcpcr_helpline_ticket_model = new DcpcrHelplineTicketModel();
                $dcpcr_helpline_ticket_model->updateCaseDetails($records, true);
                log_message("info", "The Cyfuture Ticket API call success, for Page - " . $page_number);
            } else {
                log_message("error", "The Cyfuture Ticket API call failed, Page - " . $page_number . "url - " . $url);
            }
        }
        );
        log_message("info", "Total Records fetched from Cyfuture Ticket API, ->" . $record_count);
    }

    /**
     * @throws ReflectionException
     */
    public function updateOperatorFormData(DateTimeInterface $from_date, DateTimeInterface $to_date)
    {
        helper('cyfuture');
        $url = get_cyfuture_ewsrecord_url();
        $record_count = download_and_process_cyfuture_api_data($url, $from_date->format("Y-m-d"),
            $to_date->format("Y-m-d"), function ($records, $page_number) use ($url) {
                if ($records) {
                    $reason_for_absenteeism_model = new ReasonForAbsenteeismModel();
                    $reason_for_absenteeism_model->updateCaseDetails($records);
                    $call_disposition_model = new CallDispositionModel();
                    $call_disposition_model->updateCaseDetails($records);
                    $high_risk_model = new HighRiskModel();
                    $high_risk_model->updateCaseDetails($records);
                    $back_to_school = new BackToSchoolModel();
                    $back_to_school->updateCaseDetails($records);
                    log_message("info", "The Cyfuture EWS record API call success, for Page - " . $page_number);
                } else {
                    log_message("error", "The Cyfuture EWS record API call failed, Page -" . $page_number . "url - " . $url);
                }
            }
        );
        log_message("info", "Total Records fetched from Cyfuture EWS record API, ->" . $record_count);
    }

    public function getCaseCount(array $school_ids, array $classes, $start, $end, array $where_status_is)
    {

        return $this->select(['detected_case.id'])
            ->join("master.student as student", "student.id=student_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();
    }


    public function getGenderWiseCaseCount($school_ids, $classes, $start, $end, $gender, $where_status_is)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['detected_case.id'])
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='$gender'")
            ->countAllResults();

    }

    public function getCaseCountGroupBy(string $group_by, array $school_ids, array $classes, $start, $end, $where_status_is): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['class', 'count(detected_case.id) as count'])
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy("$group_by")
            ->findAll();

    }

    public function getFrequentDetectedCases($school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([
            $this->table . '.student_id',
            'count(*) as detected_count',
            'student.name as student_name',
            'student.gender',
            'student.class',
            'student.mobile',
            'student.section',
            'student.dob',
            'student.mother',
            'student.father',
            'student.mobile',
            'student.address as address',
            'school.id as school_id',
            'school.name as school_name',
            'school.district',
            'school.zone',
        ])
            ->join($master_db . '.student as student', 'student.id = ' . $this->table . '.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();

    }

    /**
     * @throws ReflectionException
     */
    public function updateDetectedStudentStatus()
    {
        $student_ids = $this->distinct()
            ->select(['student_id'])
            ->findAll();
        foreach ($student_ids as $student_id) {
            $latest_status_data = $this->select(['student_id', 'id as case_id', 'status'])
                ->where("student_id", $student_id["student_id"])
                ->orderBy("id", "DESC")
                ->findAll("1");
            $latest_student_status_model = new LatestStudentStatusModel();
            $latest_student_status_model->updateStudentStatus($latest_status_data);
        }
    }
}

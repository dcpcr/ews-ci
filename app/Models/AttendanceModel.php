<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use DateTimeImmutable;
use DateTimeInterface;
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

    public function downloadAttendance(string $file_name, DateTimeInterface $from_date, DateTimeInterface $to_date)
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
                            $data_array[$i]['date'] = $date->format("Y-m-d");
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
        log_message("info", "Attendance records dumped in DB. From date - " .
            $from_date->format("d/m/Y") . " To Date - " . $to_date->format("d/m/Y"));
    }

    public function getStudentAttendanceForLastNDaysFrom($student_id, $date, $N): array
    {
        return $this->select(["DATE_FORMAT(date,'%y-%m-%d') as day", "attendance_status"])
            ->where("student_id = '$student_id' and 
                date <= STR_TO_DATE('" . $date->format("d/m/Y") . "','%d/%m/%Y')")
            ->orderBy("day", "desc")->findAll($N);
    }

    public function getStudentAttendanceBetween($student_id, $from_date, $to_date): array
    {
        return $this->select(["DATE_FORMAT(date,'%y-%m-%d') as day", "attendance_status"])
            ->where("student_id = '$student_id' and 
            date <= STR_TO_DATE('" . $to_date->format("d/m/Y") . "','%d/%m/%Y') and 
            date >= STR_TO_DATE('" . $from_date->format("d/m/Y") . "','%d/%m/%Y')")
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

    public function getDailyAttendanceReportForSchool($school_id, $date): array
    {
        return $this->select(
            ["school_id",
                "class",
                "count(CASE WHEN attendance_status='p' THEN 'p' ELSE null end) as present_count,
                 count(CASE WHEN attendance_status='a' THEN 'a' ELSE null end) as absent_count,
                 count(CASE WHEN attendance_status='l' THEN 'l' ELSE null end) as leave_count"
            ])
            ->join("master.student as student", "student.id=student_id")
            ->where("date", $date->format("Y-m-d"))
            ->where("school_id", $school_id)
            ->groupBy("class")
            ->findAll();
    }

    public function prepareAttendanceFormLastNDays($N, $school_ids, $classes, $date): array
    {
        $attendance_data = [];
        for ($i = 0; $i < $N; $i++) {
            $result = $this->getDateWiseMarkedStudentAttendanceCount($school_ids, $classes, $date);
            $attendance_data[] = [
                "date" => $date->format("d-m-Y"),
                "count" => (is_null($result[0]["count"])) ? "0" : $result[0]['count'],
            ];
            $date = $date->modify('-1 day');

        }
        return $attendance_data;
    }

    public function getDateWiseMarkedStudentAttendanceCount($school_ids, $classes, $date): array
    {
        return $this->select(['count(*) as count'])
            ->join("master.student as student", "student.id=student_id")
            ->where("attendance.date = STR_TO_DATE('" . $date->format("Y-m-d") . "', '%Y-%m-%d')")
            ->whereIn("school_id", $school_ids)
            ->whereIn("class", $classes)
            ->findAll();
    }

    public function getLastDayMarkedStudentAttendanceCount($school_ids, $classes, string $group_by): array
    {
        $max_date = $this->getLatestMarkedAttendanceDate();
        return $this->select(["school_id", "class", "count(*) as count"])
            ->join("master.student as student", "student.id=student_id")
            ->where("date", $max_date[0]['date'])
            ->whereIn("school_id", $school_ids)
            ->whereIn("class", $classes)
            ->groupBy("$group_by")
            ->findAll();
    }

    public function getLatestMarkedAttendanceDate(): array
    {
        return $this->select("max(date) as date")
            ->findAll();
    }

    public function getLatestMarkedAttendanceDateFor($school_id): array
    {
        return $this->select("max(date) as date")
            ->join("master.student as student", "student.id=student_id")
            ->whereIn("school_id",$school_id)
            ->findAll();
    }

    public function getClassWiseMarkedAttendance($school_ids, $classes): array
    {
        helper('general');
        $max_date = $this->getLatestMarkedAttendanceDate();
        return $this->select([
            'count(distinct(student.id)) as count_att',
            'count(student.id)/count(distinct(attendance.date)) as avg_att',
            'count(distinct(attendance.date)) as days_att',
            'school_id',
            'class',
        ])
            ->join('master.student', 'student_id = student.id', "left")
            ->whereIn('school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("date", $max_date[0]['date'])
            ->groupBy('class')
            ->findAll();
    }

    public function getPresentMarkedDateAfter($date, $student_id)
    {
        $data = $this->select(['date'])
            ->where("date >", $date)
            ->where("student_id", $student_id)
            ->where("attendance_status", "p")
            ->findAll("1");
        if (!empty($data)) {
            return $data[0]['date'];
        } else {
            return [];
        }
    }

    public function getDateWiseMarkedStudentAttendanceCountForHomePage($school_ids, $classes, $date): array
    {
        $data = $this->select(['count(*) as attendance_count'])
            ->join("master.student as student", "student.id=student_id")
            ->where("attendance.date",$date)
            ->whereIn("school_id", $school_ids)
            ->whereIn("class", $classes)
            ->findAll();
        return $data[0];
    }
}

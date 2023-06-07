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
                            "total_attendance_marked" => 0,
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
                                "total_attendance_marked" => $row['present_count'] + $row['absent_count'] + $row['leave_count'],
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

    public function getDateWiseMarkedStudentAttendanceCount($school_ids, $classes, $start, $end): array
    {
        $res = $this->select(['date', 'sum(total_attendance_marked) as attendance_count', 'sum(total_student) as total_student'])
            ->whereIn($this->table . ".school_id", $school_ids)
            ->whereIn($this->table . ".class", $classes)
            ->groupBy("date")
            ->orderBy("date", "DESC")
            ->findAll("30");
        $data = [];
        foreach ($res as $row) {
            if (date('l', strtotime($row['date'])) != "Sunday") {
                $data[] = [
                    "date" => $row['date'],
                    "attendance_count" => $row['attendance_count'],
                    "total_student" => $row['total_student']
                ];
            }

        }
        return $data;
    }

    public function getMarkedStudentAttendanceDataGroupByCount($school_ids, $classes, $latest_date, $group_by, $graph): array
    {
        if ($group_by == "district") {
            $response = $this->select(['d.name as district', 'date', 'sum(total_attendance_marked) as attendance_count', 'sum(total_student) as total_student'])
                ->whereIn($this->table . ".school_id", $school_ids)
                ->whereIn($this->table . ".class", $classes)
                ->join("master.school as s", "s.id=attendance_report.school_id")
                ->join("master.school_mapping as sm", "sm.school_id=s.id")
                ->join("master.district as d", "d.id=sm.district_id")
                ->join("master.zone as z", "z.id=sm.zone_id")
                ->where("date", $latest_date[0]['date'])
                ->groupBy("district")
                ->orderBy("district")
                ->findAll();
            foreach ($response as $row) {
                $data[] = [
                    "district" => $row['district'],
                    "Attendance_Marked" => (isset($row['attendance_count'])) ? $row['attendance_count'] : 0,
                    "Attendance_Marked_Percent" => (isset($row['attendance_count'])) ? floor($row['attendance_count'] / $row['total_student'] * 100) : 0,
                    "Total_Students" => (isset($row['total_student'])) ? $row['total_student'] : 0,
                ];
            }
            return $data;
        }

        if ($group_by == "zone") {
            $response = $this->select(['z.name as zone', 'date', 'sum(total_attendance_marked) as attendance_count', 'sum(total_student) as total_student'])
                ->whereIn($this->table . ".school_id", $school_ids)
                ->whereIn($this->table . ".class", $classes)
                ->join("master.school as s", "s.id=attendance_report.school_id")
                ->join("master.school_mapping as sm", "sm.school_id=s.id")
                ->join("master.district as d", "d.id=sm.district_id")
                ->join("master.zone as z", "z.id=sm.zone_id")
                ->where("date", $latest_date[0]['date'])
                ->groupBy("zone")
                ->orderBy("zone")
                ->findAll();
            foreach ($response as $row) {
                $data[] = [
                    "zone" => $row['zone'],
                    "Attendance_Marked" => (isset($row['attendance_count'])) ? $row['attendance_count'] : 0,
                    "Attendance_Marked_Percent" => (isset($row['attendance_count'])) ? floor($row['attendance_count'] / $row['total_student'] * 100) : 0,
                    "Total_Students" => (isset($row['total_student'])) ? $row['total_student'] : 0,
                ];
            }
            return $data;
        } elseif ($group_by == "class") {
            $data = $this->getArr($group_by, $school_ids, $classes, $latest_date[0]['date'], $graph);
        } elseif ($group_by == "school_id") {
            $data = $this->getArr($group_by, $school_ids, $classes, $latest_date[0]['date'], $graph);
        }

        return $data;

    }

    public function getMarkedSchoolAttendance($school_ids, $classes, $start, $end): array
    {
        $attendance_data = $this->select([
            'sum(total_attendance_marked) as count_att',
            'sum(total_attendance_marked)/count(distinct date) as avg_att',
            'count(distinct date) as days_att',
            'school_id'
        ])
            ->whereIn('school_id', $school_ids)
            ->whereIn('class', $classes)
            ->where('total_attendance_marked!=', 0)
            ->where("date BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->groupBy('school_id')
            ->findAll();

        $student_count_model = new StudentCountModel();
        $total_student_count_data = $student_count_model->getSchoolWiseStudentCount($school_ids, $classes);
        $data = [];
        $count = 1;
        foreach ($total_student_count_data as $row) {
            $data[$row['school_id']] = [
                "Serial_no" => $count++,
                "School" => $row['school_id'] . "-" . $row['school_name'],
                "District" => $row['district_name'],
                "Zone" => $row['zone_name'],
                "Total_Students" => $row['total_student'],
                "Average_Attendance_Marked" => 0,
                "Attendance_Marked_Days" => 0
            ];
        }
        foreach ($attendance_data as $row) {
            $data [$row['school_id']] ['Average_Attendance_Marked'] = is_null($row['avg_att']) ? 0 : $row['avg_att'];
            $data [$row['school_id']] ['Attendance_Marked_Days'] = is_null($row['days_att']) ? 0 : $row['days_att'];
        }
        $final_data = [];
        foreach ($data as $row) {
            $final_data[] = $row;

        }
        return $final_data;
    }

    public function getMarkedSchoolAttendanceNew($school_ids, $classes, $start, $end): array
    {
        $count = 1;
        $student_count_model = new StudentCountModel();
        $attendance_data = [];
        foreach ($school_ids as $school_id) {
            $data = $this->select([
                'sum(total_attendance_marked) as count_att',
                'sum(total_attendance_marked)/count(distinct date) as avg_att',
                'count(distinct date) as days_att',
                'school_id'
            ])
                ->where('school_id', $school_id)
                ->whereIn('class', $classes)
                ->where('total_attendance_marked!=', 0)
                ->where("date BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                    $end . "', '%m/%d/%Y')")
                ->findAll();
            $total_student_count_data = $student_count_model->getSchoolWiseStudentCount([$school_id], $classes);
            if(!empty($total_student_count_data)){
                $attendance_data[] = [
                    "Serial_no" => $count++,
                    "School" => $total_student_count_data[0]['school_id'] . "-" . $total_student_count_data[0]['school_name'],
                    "class" => $total_student_count_data[0]['class'],
                    "Total_Students" => $total_student_count_data[0]['total_student'],
                    "District" => $total_student_count_data[0]['district_name'],
                    "Zone" => $total_student_count_data[0]['zone_name'],
                    "Average_Attendance_Marked" => $data[0]['avg_att'],
                    "Attendance_Marked_Days" => $data[0]['days_att'],
                ];
            }
        }
        return $attendance_data;
    }

    /**
     * @param $group_by
     * @param $school_ids
     * @param $classes
     * @param $date
     * @return array
     */
    public function getArr($group_by, $school_ids, $classes, $date, $graph): array
    {
        $response = $this->select(['s.name as name', 'z.name as zone', 'd.name as district', "attendance_report.$group_by as $group_by", 'date', 'sum(total_attendance_marked) as attendance_count', 'sum(total_student) as total_student'])
            ->whereIn($this->table . ".school_id", $school_ids)
            ->whereIn($this->table . ".class", $classes)
            ->join("master.school as s", "s.id=attendance_report.school_id")
            ->join("master.school_mapping as sm", "sm.school_id=s.id")
            ->join("master.district as d", "d.id=sm.district_id")
            ->join("master.zone as z", "z.id=sm.zone_id")
            ->where("date", $date)
            ->groupBy("$group_by")
            ->orderBy("$group_by")
            ->findAll();


        $count = 1;
        $data = [];
        $school_name = '';
        foreach ($response as $row) {
            if ($group_by == 'school_id') {
                $school_name = "-" . $row["name"];
            }
            if ($graph) {
                $school_name = "";
            }
            $data[] = [
                "Serial_no" => $count++,
                "$group_by" => $row["$group_by"] . $school_name,
                "district" => $row['district'],
                "zone" => $row['zone'],
                "Attendance_Marked" => (isset($row['attendance_count'])) ? $row['attendance_count'] : 0,
                "Attendance_Marked_Percent" => (isset($row['attendance_count'])) ? floor($row['attendance_count'] / $row['total_student'] * 100) : 0,
                "Total_Students" => (isset($row['total_student'])) ? $row['total_student'] : 0,
            ];
        }
        return $data;
    }
}

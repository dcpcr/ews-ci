<?php

namespace App\Models;

use CodeIgniter\Model;

class LatestStudentStatusModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'latest_student_status';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["student_id", "status", "case_id"];


    /**
     * @throws \ReflectionException
     */
    public function updateStudentStatus($latest_status_data)
    {

        $insert_count = $this->ignore()->insertBatch($latest_status_data, null, count($latest_status_data));

        if (!$insert_count) {
            $update_count = $this->updateBatch($latest_status_data, 'student_id', count($latest_status_data));
            if ($update_count) {
                log_message("info", "status update for student_id-> " . $latest_status_data[0]['student_id']);
            }
        } else {
            log_message("info", "new record inserted for student_id-> " . $latest_status_data[0]['student_id']);

        }

    }

    public function getDetectedStudentCount(array $school_ids, array $classes, $start, $end, array $where_status_is)
    {

        return $this->select(['case_id'])
            ->join("master.student as student", "student.id=student_id")
            ->join("detected_case", "detected_case.id=case_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("latest_student_status.status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();
    }

    public function getDetectedStudentGenderWiseCount($school_ids, $classes, $start, $end, $gender, $where_status_is)
    {
        return $this->select(['case_id'])
            ->join('master.student as student', 'student.id = student_id')
            ->join("detected_case", "detected_case.id=case_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("latest_student_status.status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='$gender'")
            ->countAllResults();

    }

    public function getDetectedStudentCountGroupBy(string $group_by, array $school_ids, array $classes, $start, $end, $where_status_is): array
    {

        return $this->select(['class', 'count(case_id) as count'])
            ->join('master.student as student', 'student.id = student_id')
            ->join("detected_case", "detected_case.id=case_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("latest_student_status.status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy("$group_by")
            ->findAll();

    }
}

<?php

namespace App\Models;

class HomeVisitModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'home_visit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'status'];

    public function getHomeVisitCount(array $school_ids, array $classes, $start, $end, string $where_status_is)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.name as reason_name', 'count(*) as count'])
            ->join('detected_case', 'detected_case.id=home_visit.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where('home_visit.status', $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();
    }

    protected function getKeys(): array
    {
        return array('case_id', 'is_home_visit_required');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "is_home_visit_required" => "status"
        );
    }
}

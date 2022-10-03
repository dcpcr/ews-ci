<?php

namespace App\Models;

class HighRiskModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'high_risk';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'status']; //if status=1 then ticket raised

    protected function getKeys(): array
    {
        return array('case_id', 'raised_ticket');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "raised_ticket" => "status"
        );
    }

    public function getHighRiskCasesCountGenderWise(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([
            'count(*) as count',
            'student.gender as gender',
        ])
            ->join('detected_case as case', 'case.id = ' . $this->table . '.case_id')
            ->join($master_db . '.student as student', 'student.id = case.student_id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->where($this->table . '.status', '1')
            ->groupBy('gender')
            ->findAll();
    }

}

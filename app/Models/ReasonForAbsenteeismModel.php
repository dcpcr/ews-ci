<?php

namespace App\Models;

class ReasonForAbsenteeismModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'reason_for_absenteeism';
    protected $primaryKey = 'case_id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = false;
    protected $allowedFields = ['case_id', 'reason_id', 'other_reason'];

    public function getReasonCategoryCount(array $school_ids, array $classes, $start, $end, array $reason_id)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select()
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn('reason_id', $reason_id)
            ->where('status!=',"Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();

    }


    public function getReasonCategoryList(array $school_ids, array $classes, $start, $end, array $reason_id)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select()
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn('reason_id', $reason_id)
            ->where('status!=',"Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->findAll();

    }



    protected function getKeys(): array
    {
        return array('case_id', 'reason_of_absense', 'other_reason_of_absense');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "reason_of_absense" => "reason_id",
            "other_reason_of_absense" => "other_reason"
        );
    }

    function getReasonsCount(array $school_ids, $classes, $start, $end, $gender): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.id as id','reason.name as reason_name', 'action_taken', 'count(*) as count'])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn('master.student.gender', $gender)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy('reason_name')
            ->orderBy('count', 'desc')
            ->findAll();
    }

    public function getCaseListByReasonId($reason_id,$school_ids,$classes,$start,$end){
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select()
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("reason.id",$reason_id)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->findAll();
    }

}

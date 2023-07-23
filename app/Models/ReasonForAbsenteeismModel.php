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
    protected $allowedFields = ['case_id', 'who_passed_away', 'who_is_suffering', 'reason_id', 'other_reason', 'sub_reasons_id'];

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
            ->where('status!=', "Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();

    }


    public function getReasonCategoryList(array $school_ids, array $classes, $start, $end, array $reason_id): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        var_dump($master_db);
        return $this->select()
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn('reason_id', $reason_id)
            ->where('status!=', "Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->findAll();

    }

    public function prepareReasonWiseDataForSickness(array $school_ids, $classes, $start, $end): array
    {
        $final_data=[];
        $data = $this->getReasonsCountSubCategory($school_ids, $classes, $start, $end, "1", "who_is_suffering");
        foreach ($data as $row) {
            if ($row['who_is_suffering'] == 1) {
                $row['who_is_suffering'] = "Sickness of Student";
            } elseif ($row['who_is_suffering'] == 2) {
                $row['who_is_suffering'] = "Sickness of Mother";
            } elseif ($row['who_is_suffering'] == 3) {
                $row['who_is_suffering'] = "Sickness of Father";
            } elseif ($row['who_is_suffering'] == 4) {
                $row['who_is_suffering'] = "Sickness of Both (Mother and Father)";
            } elseif ($row['who_is_suffering'] == 5) {
                $row['who_is_suffering'] = "Sickness of Other Family Member";
            }
            $final_data[]=$row;
        }
        return $final_data;
    }

    public function prepareReasonWiseDataForDeath(array $school_ids, $classes, $start, $end): array
    {
        $final_data=[];
        $data = $this->getReasonsCountSubCategory($school_ids, $classes, $start, $end, "22", "who_passed_away");
        foreach ($data as $row) {
            if ($row['who_passed_away'] == 1) {
                $row['who_passed_away'] = "Death of Mother";
            } elseif ($row['who_passed_away'] == 2) {
                $row['who_passed_away'] = "Death of Father";
            } elseif ($row['who_passed_away'] == 3) {
                $row['who_passed_away'] = "Death of Student";
            } elseif ($row['who_passed_away'] == 4) {
                $row['who_passed_away'] = "Death of Other Family Member";
            }
            $final_data[]=$row;
        }
        return $final_data;
    }

    public function getCaseListByReasonIdForSickness($id, $reason_name, array $school_ids, array $classes, $start, $end): array
    {

        $reason_name=str_replace("_", " ", $reason_name);
        if ($reason_name == "Sickness of Student") {
           $who_is_suffering=1;
        }elseif($reason_name == "Sickness of Mother") {
            var_dump($reason_name);
            $who_is_suffering=2;
        }elseif ($reason_name == "Sickness of Father"){
            $who_is_suffering=3;
        }elseif ($reason_name == "Sickness of Both (Mother and Father)"){
            $who_is_suffering=4;
        }elseif ($reason_name == "Sickness of Other Family Member"){
            $who_is_suffering=5;
        }
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(["CASE WHEN who_is_suffering=$who_is_suffering THEN '$reason_name' end as reason_name",
            'student.name as student_name',
            'detected_case.id as case_id',
            'student_id',
            'status',
            'student.district as district',
            'detected_case.id',
            'dob',
            'class',
            'section',
            'student.gender',
            'father',
            'mother',
            'student.mobile',
            'student.address',
            'student.school_id',
            "seven_days_criteria",
            "thirty_days_criteria",
            "date_of_bts",
            "system_bts",
            "day",
            "priority",
        ])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->where('reason_for_absenteeism.reason_id', $id)
            ->where('who_is_suffering', $who_is_suffering)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->findAll();
    }

    public function getCaseListByReasonIdForDeath($id, $reason_name, array $school_ids, array $classes, $start, $end): array
    {
        $reason_name=str_replace("_", " ", $reason_name);
        if ($reason_name == "Death of Mother") {
            $who_passed_away=1;
        }elseif($reason_name == "Death of Father") {
            $who_passed_away=2;
        }elseif ($reason_name == "Death of Student"){
            $who_passed_away=3;
        }elseif ($reason_name == "Death of Other Family Member"){
            $who_passed_away=4;
        }
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(["CASE WHEN who_passed_away=$who_passed_away THEN '$reason_name' end as reason_name",
            'student.name as student_name',
            'detected_case.id as case_id',
            'student_id',
            'status',
            'student.district as district',
            'detected_case.id',
            'dob',
            'class',
            'section',
            'student.gender',
            'father',
            'mother',
            'student.mobile',
            'student.address',
            'student.school_id',
            "seven_days_criteria",
            "thirty_days_criteria",
            "date_of_bts",
            "system_bts",
            "day",
            "priority",
        ])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->where('reason_for_absenteeism.reason_id', $id)
            ->where('who_passed_away', $who_passed_away)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->findAll();
    }


    protected function getKeys(): array
    {
        return array('case_id', 'reason_of_absense', 'other_reason_of_absense', 'who_passed_away', 'suffereing_from_sickness');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "reason_of_absense" => "reason_id",
            "who_passed_away" => "who_passed_away",
            "suffereing_from_sickness" => "who_is_suffering",
            "other_reason_of_absense" => "other_reason"
        );
    }

    function getReasonsCount(array $school_ids, $classes, $start, $end, $gender): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.id as id', 'reason.name as reason_name', 'action_taken', 'count(*) as count'])
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

    public function getCaseListByReasonId($reason_id, $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(["*", "master.student.name as"])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("reason.id", $reason_id)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->findAll();
    }

    private function getReasonsCountSubCategory(array $school_ids, $classes, $start, $end, $id, string $column_name): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select([$column_name, 'reason.id as id', 'reason.name as reason_name', 'action_taken', 'count(*) as count'])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->where('reason_for_absenteeism.reason_id', $id)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy($column_name)
            ->findAll();
    }

    public function getReasonsCountCategoriesOtherThanSicknessAndDeath(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.id as id', 'reason.name as reason_name', 'action_taken', 'count(*) as count'])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereNotIn('reason_id ', ['1', '22'])
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy('reason_name')
            ->findAll();
    }

}

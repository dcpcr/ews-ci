<?php

namespace App\Models;

use CodeIgniter\Model;

class ReasonForAbsenteeismModel extends Model
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


    /**
     * @throws \ReflectionException
     */
    function insertUpdateCaseReason($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $reasonData = extract_reason_data_from_cases($cases);
            $keyMapping = array(
                "reason_of_absense" => "reason_id",
                "other_reason_of_absense" => "other_reason"
            );
            $tableData = prepare_data_for_table($reasonData, $keyMapping);
            $count = $this->ignore()->insertBatch($tableData,null,2000);
            log_message("info", "$count New Records inserted in reason_for_absenteeism table.");
            $count = $this->updateBatch($tableData, 'case_id', 2000);
            log_message("info", "$count Records updated in reason_for_absenteeism table.");
        }
    }
    function getReasonsCount(array $school_ids, $classes, $start, $end, $gender): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['reason.name as reason_name', 'count(*) as count'])
            ->join('reason', 'reason.id=reason_for_absenteeism.reason_id')
            ->join('detected_case', 'detected_case.id=reason_for_absenteeism.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='$gender'")
            ->groupBy('reason_name')
            ->orderBy('count','desc')
            ->findAll();
    }
}

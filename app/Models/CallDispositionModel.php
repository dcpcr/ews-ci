<?php

namespace App\Models;

use CodeIgniter\Model;

class CallDispositionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'call_disposition';
    protected $primaryKey       = 'case_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['case_id','call_disposition_id'];

    /**
     * @throws \ReflectionException
     */
    function insertUpdateCallDisposition($cases)
   {
       helper('cyfuture');
       if ($cases) {
           $reasonData = extract_call_disposition_data_from_cases($cases);
           $keyMapping = array(
               "call_dis" => "call_disposition_id"
           );
           $tableData = prepare_data_for_table($reasonData, $keyMapping);
           $this->ignore(true)->insertBatch($tableData);
           $this->updateBatch($tableData, 'case_id');
       }
   }
    function getCallDisposition(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['name', 'count(*) as count'])
            ->join('detected_case', 'detected_case.id=call_disposition.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->join('call_disposition_master', 'call_disposition_master.id = call_disposition.call_disposition_id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy('name')
            ->findAll();
    }
}

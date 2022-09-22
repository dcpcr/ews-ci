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
}

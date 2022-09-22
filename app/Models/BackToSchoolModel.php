<?php

namespace App\Models;

use CodeIgniter\Model;

class BackToSchoolModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'back_to_school';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'status'];

    /**
     * @throws \ReflectionException
     */
    function insertUpdateBackToSchool($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $backToSchoolData = extract_back_to_school_data_from_cases($cases);
            $keyMapping = array(
                "will_student_be_able_to_join_school" => "status"
            );
            $tableData = prepare_data_for_table($backToSchoolData, $keyMapping);
            $count = $this->ignore()->insertBatch($tableData,null,2000);
            log_message("info", "$count New Records inserted in back_to_school table.");
            $count = $this->updateBatch($tableData, 'case_id', 2000);
            log_message("info", "$count Records updated in back_to_school table.");
        }
    }
}

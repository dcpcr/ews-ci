<?php

namespace App\Models;

use CodeIgniter\Model;

class BackToSchoolModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'back_to_school';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['case_id','status'];


    /**
     * @throws \ReflectionException
     */
    function insertUpdateBackToSchool($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $backToSchoolData = extractBackToSchoolDataFromCases($cases);
            $keyMapping = array(
                "will_student_be_able_to_join_school" => "status"
            );
            $tableData = prepareDataforTable($backToSchoolData, $keyMapping);
            $this->ignore(true)->insertBatch($tableData);
            $this->updateBatch($tableData, 'case_id');
        }
    }
}

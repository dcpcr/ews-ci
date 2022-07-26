<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeVisitModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'home_visit';
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
    function insertUpdateHomeVisit($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $highRiskData = extractHomeVisitDataFromCases($cases);
            $keyMapping = array(
                "is_home_visit_required" => "status"
            );
            $tableData = prepareDataforTable($highRiskData, $keyMapping);
            $this->ignore(true)->insertBatch($tableData);
            $this->updateBatch($tableData, 'case_id');
        }
    }
}

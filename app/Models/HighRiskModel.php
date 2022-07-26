<?php

namespace App\Models;

use CodeIgniter\Model;

class HighRiskModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'high_risk';
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
    function insertUpdateHighRisk($cases)
   {
       helper('cyfuture');
       if ($cases) {
           $highRiskData = extractHighRiskDataFromCases($cases);
           $keyMapping = array(
               "raised_ticket" => "status"
           );
           $tableData = prepareDataforTable($highRiskData, $keyMapping);
           $this->ignore(true)->insertBatch($tableData);
           $this->updateBatch($tableData, 'case_id');
       }
   }
}

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
//if status=1 then ticket raised

    /**
     * @throws \ReflectionException
     */
    function insertUpdateHighRisk($cases)
   {
       helper('cyfuture');
       if ($cases) {
           $highRiskData = extract_high_risk_data_from_cases($cases);
           $keyMapping = array(
               "raised_ticket" => "status"
           );
           $tableData = prepare_data_for_table($highRiskData, $keyMapping);
           $count = $this->ignore()->insertBatch($tableData,null,2000);
           log_message("info", "$count New Records inserted in high_risk table.");
           $count = $this->updateBatch($tableData, 'case_id', 2000);
           log_message("info", "$count Records updated in high_risk table.");
       }
   }
}

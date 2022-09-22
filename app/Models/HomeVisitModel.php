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
            $highRiskData = extract_home_visit_data_from_cases($cases);
            $keyMapping = array(
                "is_home_visit_required" => "status"
            );
            $tableData = prepare_data_for_table($highRiskData, $keyMapping);
            $count = $this->ignore()->insertBatch($tableData,null,2000);
            log_message("info", "$count New Records inserted in home_visit table.");
            $count = $this->updateBatch($tableData, 'case_id', 2000);
            log_message("info", "$count Records updated in home_visit table.");
        }
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DcpcrHelplineTicketModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'dcpcr_helpline_ticket';
    protected $primaryKey = 'case_id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id','status','sub_status','division','sub_division','nature'];

    /**
     * @throws \ReflectionException
     */
    function insertUpdateDcpcrTicketDetails($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $highRiskData = extractDcpcrHelplineTicketDataFromCases($cases);
            $keyMapping = array(
                "name_division"=> "division",
                "sub_name_division"=> "sub_division",
                "nature_case"=> "nature",
            );
            $tableData = prepareDataforTable($highRiskData, $keyMapping);
            $this->ignore(true)->insertBatch($tableData);
            $this->updateBatch($tableData, 'case_id');
        }
    }
}

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
            $highRiskData = extract_dcpcr_helpline_ticket_data_from_cases($cases);
            $keyMapping = array(
                "name_division"=> "division",
                "sub_name_division"=> "sub_division",
                "nature_case"=> "nature",
            );
            $tableData = prepare_data_for_table($highRiskData, $keyMapping);
            $this->ignore(true)->insertBatch($tableData);
            $this->updateBatch($tableData, 'case_id');
        }
    }
}
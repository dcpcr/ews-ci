<?php

namespace App\Models;

use CodeIgniter\Model;

class DcpcrHelplineTicketModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'dcpcr_helpline_ticket';
    protected $primaryKey = 'ticket_number';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id', 'ticket_id', 'ticket_number', 'status', 'sub_status', 'division', 'sub_division', 'nature'];

    /**
     * @throws \ReflectionException
     */
    function insertDcpcrTicketDetails($cases)
    {
        helper('cyfuture');
        if ($cases) {
            $ticketData = extract_dcpcr_helpline_ticket_data_from_cases($cases);
            $keyMapping = array(
                "ticket_num" => "ticket_number",
            );

            $tableData = prepare_data_for_table($ticketData, $keyMapping);
            $count = $this->ignore()->insertBatch($tableData, null, 2000);
            log_message("info", "$count New Records inserted in dcpcr_helpline_ticket table.");
        }
    }

    /**
     * @throws \ReflectionException
     */
    function updateDcpcrTicketDetails()
    {
        helper('nsbbpo');
        $ticket_numbers = $this->select('ticket_number')
            ->orderBy('ticket_number')
            ->findAll();
        $counter = 1;
        $data = [];
        foreach ($ticket_numbers as $ticket) {
            $response = download_ticket_details($ticket['ticket_number']);
            $json_decoded = json_decode($response);
            $ticket = $json_decoded[0]->TicketNo;
            $status = $json_decoded[0]->Status;
            $data [] = [
                "ticket_number" => $json_decoded[0]->TicketNo,
                "status" => $json_decoded[0]->Status,
                "sub_status" => $json_decoded[0]->Substatus,
                "division" => $json_decoded[0]->Division,
                "sub_division" => $json_decoded[0]->SubDivision
            ];
            $counter++;
            $response = $this->update("ticket_number", $data);
            log_message('info', "Total $response Tickets details has been updated");

        }

    }

    //This function checks is there is any open ticket for the case in the system
    public function isTicketNotOpen($case_id): bool
    {
        $tickets_status = $this->select(['ticket_number', 'if(status="closed", 1, 0) as status'])
            ->where("case_id", $case_id)
            ->findAll();
        $flag = 1;
        if (!empty($tickets_status)) {
            foreach ($tickets_status as $row) {
                $flag = $flag * $row['status'];
            }
        }
        return $flag;
    }

}

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

    private function getTicketNumbers()
    {
        return $this->select('ticket_number')
            ->orderBy('ticket_number')
            ->findAll();

    }

    /**
     * @throws \ReflectionException
     */
    public function updateTicketDetails()
    {
        $ticket_numbers = $this->getTicketNumbers();
        helper('nsbbpo');
        $counter = 1;
        $data = [];
        foreach ($ticket_numbers as $ticket) {
            $response = download_ticket_details($ticket['ticket_number']);
            if (!empty($response)) {
                $ticket_number = $response->TicketNo;
                $data [] = [
                    "ticket_number" => $response->TicketNo,
                    "status" => $response->Status,
                    "sub_status" => $response->Substatus,
                    "division" => $response->Division,
                    "sub_division" => $response->SubDivision
                ];
                $counter++;
                log_message('info', "Ticket Number $ticket_number details has been fetched");
                sleep(1);
            } else {
                log_message('notice', "Ticket Number " . $ticket['ticket_number'] . " details not fetched");
            }
        }
        $batchSize = count($data);
        $response = $this->updateBatch($data, "ticket_number", $batchSize);
        log_message('info', "Total $response Tickets details has been updated");
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

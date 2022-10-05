<?php

namespace App\Models;

class DcpcrHelplineTicketModel extends CaseDetailsModel
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


    protected function getKeys(): array
    {
        return array('case_id', 'ticket_id', 'ticket_num', 'created_at',);
    }

    protected function getKeyMappings(): array
    {
        return array(
            "ticket_num" => "ticket_number",
        );
    }

    private function getTicketNumbers(): array
    {
        return $this->select('ticket_number')
            ->where("status!=", "closed")
            ->orderBy('ticket_number')
            ->findAll();
    }

    /**
     * @throws \ReflectionException
     */
    public function updateOpenTicketFromNsbbpo()
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
                sleep(8);
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

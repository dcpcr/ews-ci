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
        if(count($ticket_numbers)==0){
            log_message("info","There is no DCPCR Helpline Ticket");
            return;
        }
        helper('nsbbpo');
        $counter=0;
        $ticket_details_updated=0;
        foreach ($ticket_numbers as $ticket) {
            if($counter % 100==0){
                sleep("5");
            }
            $data = [];
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
                $batchSize = count($data);
                $update_response = $this->updateBatch($data, "ticket_number", $batchSize);
                $counter++;
                if($update_response!=0){
                    $ticket_details_updated++;
                    log_message('info', "Ticket Number $ticket_number details has been updated:");
                }
            } else {
                log_message('notice', "Ticket Number " . $ticket['ticket_number'] . " details not fetched");
            }
        }
        log_message("info","Total DCPCR Helpline tickets updated:".$ticket_details_updated);
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

    public function getDcpcrHelplineCaseDetails($school_ids, $classes, $start, $end, $where_status_is, $column_name): array
    {
        return $this->select(['sub_division', "count(ticket_number) as $column_name"])
            ->join("detected_case as case", "case.id=case_id")
            ->join("master.student as student", "student.id=case.student_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereIn("dcpcr_helpline_ticket.status", $where_status_is)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->where("sub_division!=","")
            ->groupBy("sub_division")
            ->orderBy("sub_division")
            ->findAll();

    }
}

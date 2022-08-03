<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsBatchModel extends Model
{
    protected $DBGroup          = 'master';
    protected $table            = 'sms_batch';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['message_id','sms_template_id','status_code','report_fetched'];


    /**
     * @throws \ReflectionException
     */
    public function insterSmsBatchData($messageId,$statusCode,$templateId): array
    {
        $array=explode('=',$messageId);
        $finalMessageId=trim($array[1]);
        $data=[
            "message_id"=>"$finalMessageId",
            "status_code"=>"$statusCode",
            "sms_template_id"=>"$templateId",
        ];
        $this->insert($data);
        return $this->selectMax("id")->get()->getResultArray();

    }

    public function getMessageId(): array
    {
        return $this->select('message_id')->where('report_fetched=','0' and 'report_fetched <','4' )->findAll();
    }


}

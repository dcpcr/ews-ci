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
    protected $allowedFields    = ['id','message_id','sms_template_id','status_code','report_fetched'];


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
        return $this->select('id,message_id')->where('report_fetched <','4')->get()->getResultArray();
    }

    /**
     * @throws \ReflectionException
     */
    public function updateReportFetchFalg($batch_id)
    {
        $this->set('report_fetched',"report_fetched+1",false)->where('id',"$batch_id")->update();
    }


}
<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsBatchModel extends Model
{
    protected $DBGroup          = 'default';
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
        $data=[
            "message_id"=>"$messageId",
            "status_code"=>"$statusCode",
            "sms_template_id"=>"$templateId",
        ];
        $this->insert($data);
        return $this->selectMax("id")->get()->getResultArray();

    }


}

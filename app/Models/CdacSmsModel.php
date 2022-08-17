<?php

namespace App\Models;

use CodeIgniter\Model;

class CdacSmsModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'cdac_sms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['id', 'message_id', 'sms_template_id', 'status_code', 'report_fetched'];


    /**
     * @throws \ReflectionException
     */
    public function insertSmsBatchData($messageId, $statusCode, $templateId): array
    {
        $array = explode('=', $messageId);
        $finalMessageId = trim($array[1]);
        $data = [
            "message_id" => "$finalMessageId",
            "status_code" => "$statusCode",
            "sms_template_id" => "$templateId",
        ];
        $this->insert($data);
        return $this->selectMax("id")->get()->getResultArray();

    }


    /**
     * @throws \ReflectionException
     */
    public function updateReportFetchFalg($batch_id)
    {
        $this->set('report_fetched', "report_fetched+1", false)->where('id', "$batch_id")->update();
    }

    /**
     * @throws \ReflectionException
     */
    public function fetchSmsDeliveryReport()
    {
        helper('cdac');
        $messageIds = $this->select('id,message_id')->where('report_fetched <', '4')->get()->getResultArray();
        if (count($messageIds) > 0) {
            for ($i = 0; $i < count($messageIds); $i++) {
                $messageId = $messageIds[$i]['message_id'];
                $batch_id = $messageIds[$i]['id'];
                fetch_sms_delivery_report($messageId, $batch_id);
                $cdac_sms_model = new CdacSmsModel();
                $cdac_sms_model->updateReportFetchFalg($batch_id);
                sleep(3);
            }
        }
    }


}

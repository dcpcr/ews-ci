<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

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
     * @throws ReflectionException
     */
    public function insertSmsBatchData($messageId, $statusCode, $templateId)
    {
        $array = explode('=', $messageId);
        $finalMessageId = trim($array[1]);
        $data = [
            "message_id" => "$finalMessageId",
            "status_code" => "$statusCode",
            "sms_template_id" => "$templateId",
        ];
        $this->insert($data);
        return $this->selectMax("id")->get();
    }


    /**
     * @throws ReflectionException
     */
    public function updateReportFetchFlag($batch_id)
    {
        $this->set('report_fetched', "report_fetched + 1", false)->where('id', "$batch_id")->update();
    }

    /**
     * @throws ReflectionException
     */
    public function downloadSmsDeliveryReport()
    {
        helper('cdac');
        $messageIds = $this->select('id, message_id')
            ->where('report_fetched <', '4')
            ->findAll();
        if (count($messageIds) > 0) {
            for ($i = 0; $i < count($messageIds); $i++) {
                $messageId = $messageIds[$i]['message_id'];
                $batch_id = $messageIds[$i]['id'];
                $response = fetch_sms_delivery_report($messageId);
                if (!empty($response)) {
                    $sms_batch = new CdacSmsStatusModel();
                    $sms_batch->insertSmsStatus($response, $batch_id);
                    $this->updateReportFetchFlag($batch_id);
                }
                sleep(3);
            }
            $student_model = new StudentModel();
            $student_model->updateSmsStatusOfMobileNumbers();
        }
    }


}
<?php

namespace App\Models;

use CodeIgniter\Model;

class CdacSmsStatusModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'cdac_sms_status';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['batch_id', 'mobile_number', 'status'];

    /**
     * @throws \ReflectionException
     */
    public function insertMobileNumbersSmsBatch($report_data)
    {

        if (!empty($report_data)) {
            $this->insertBatch($report_data);
        }
    }


    public function fetchLatestSmsDeliveryReportOfMobileNumbers($mobile_number)
    {

        if (!empty($mobile_number)) {
            $mobile_number = "91" . $mobile_number;
            $subQuery = $this->selectMax('created_at')->where('mobile_number', $mobile_number)->get()->getResultArray();
            $created_at = $subQuery[0]['created_at'];
            $result = $this->select('status')->Where('created_at', $created_at)->where('mobile_number', $mobile_number)->findAll();
            return $result['0']['status'];
        } else {
            return "10 digit mobile number is required as parameter";
        }


    }
}

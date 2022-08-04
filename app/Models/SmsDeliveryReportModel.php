<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsDeliveryReportModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'sms_delivery_report';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['sms_batch_id', 'mobile_number', 'status'];

    /**
     * @throws \ReflectionException
     */
    public function insertMobileNumbersSmsBatch($report_data)
    {

        if (!empty($report_data)) {
            $this->insertBatch($report_data);
        }
    }
}

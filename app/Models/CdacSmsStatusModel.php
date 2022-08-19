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
            $mobile_number_string = '';
            foreach ($mobile_number as $row) {
                $mobile_number_string .= "91" . $row['mobile'] . ',';
            }
            $mobile_number_string = substr($mobile_number_string, 0, strlen($mobile_number_string) - 1);
            $sql = 'SELECT id,batch_id,SUBSTR(mobile_number, 3, 10) as mobile,status,created_at FROM `cdac_sms_status` where(mobile_number, created_at) in(select mobile_number,max(`created_at`) as created_at from cdac_sms_status where mobile_number in(' . $mobile_number_string . ') group by mobile_number)';
            return $this->db->query($sql)->getResultArray();
        }
    }
}

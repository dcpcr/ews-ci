<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class CdacSmsStatusModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'cdac_sms_status';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['batch_id', 'mobile_number', 'status'];

    /**
     * @throws ReflectionException
     */
    public function insertSmsStatus($response, $batch_id)
    {
        $cdac_report_data = [];
        $mobile_report_data = [];
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $response) as $line) {
            $line_arr = explode(',', $line);
            $cdac_report_data[] = array(
                'batch_id' => $batch_id,
                'mobile_number' => substr($line_arr[0], 2, 10),
                'status' => $line_arr[1]
            );
            $mobile_report_data[] = array(
                'mobile' => substr($line_arr[0], 2, 10),
                'status' => $line_arr[1]
            );
        }
        if (!empty($cdac_report_data)) {
            $this->insertBatch($cdac_report_data);
        }
        if (!empty($mobile_report_data)) {
            $mobile_status_model = new MobileSmsStatusModel();
            $mobile_status_model->updateBatch($mobile_report_data, 'mobile');
        }
    }
}

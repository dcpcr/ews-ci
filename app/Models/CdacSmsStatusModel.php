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
    public function insertSmsStatus($response, $batch_id)
    {
        $report_data = [];
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $response) as $line){
            $line_arr = explode(',', $line);
            $report_data[] = array(
                'batch_id' => $batch_id,
                'mobile_number' => $line_arr[0],
                'status' => $line_arr[1]
            );
        }
        if (!empty($report_data)) {
            $this->insertBatch($report_data);
        }
    }
}

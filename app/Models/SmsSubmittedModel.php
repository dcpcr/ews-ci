<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsSubmittedModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sms_submitted';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['sms_batch_id', 'mobile_number', 'delivery_status'];


    /**
     * @throws \ReflectionException
     */
    public function insertMobileNumbersSmsBatch($batch, $mobilenos)
    {
        $mobile_numbers = explode(',', $mobilenos);
        $id="";
        foreach ($batch as $batchid) {
            $id = $batchid;
        }
        $ids = implode(',', $id);
        foreach ($mobile_numbers as $mobile) {
            $data[] = [
                'sms_batch_id' => $ids,
                'mobile_number' => $mobile,
            ];
        }
        if (!empty($data)) {
            $this->insertBatch($data);
        }

    }
}

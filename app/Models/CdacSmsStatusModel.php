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
        $response = preg_replace('/\s/', ',', $response);
        $response_arr = explode(',', $response);
        $j = 0;
        $k = 1;
        for ($i = 0; $i < count($response_arr) / 4; $i++) {
            $report_data[] = array(
                'batch_id' => $batch_id,
                'mobile_number' => $response_arr["$j"],
                'status' => $response_arr["$k"]
            );
            $j = $j + 4;
            $k = $k + 4;
        }
        if (!empty($report_data)) {
            $this->insertBatch($report_data);
        }
    }

    public function fetchLatestSmsStatusOf($mobile_numbers)
    {
        if (!empty($mobile_numbers)) {
            array_walk($mobile_numbers, function (&$item) {
                $item = "91" . $item;
            });
            $sub_query = $this->select([
                'mobile_number',
                'max(`created_at`) as created_at',
            ])
                ->whereIn('mobile_number', $mobile_numbers)
                ->groupBy('mobile_number')
                ->getCompiledSelect();

            $builder = $this->select([
                'id',
                'batch_id',
                'SUBSTR(mobile_number, 3, 10) as mobile',
                'status',
                'created_at'
            ])
                ->join('(' . $sub_query . ') `s1`', 'mobile_number = s1.mobile_number AND created_at = s1.created_at');
            $query = $builder->get();
            return $query->getResultArray();
        }
        return [];
    }
}

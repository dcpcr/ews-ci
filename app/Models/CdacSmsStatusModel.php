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

    public function fetchLatestSmsStatusOf($mobile_numbers): array
    {
        if (!empty($mobile_numbers)) {
            array_walk($mobile_numbers, function (&$item) {
                $item = "91" . $item['mobile'];
            });
            $sub_query = $this->select([
                'mobile_number as m',
                'max(`created_at`) as c',
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
                ->join('(' . $sub_query . ') `s1`', 'mobile_number = s1.m AND created_at = s1.c');
            $query = $builder->get();
            return $query->getResultArray();
        }
        return [];
    }
}

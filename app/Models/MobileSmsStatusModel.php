<?php

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class MobileSmsStatusModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'mobile_sms_status';
    protected $primaryKey = 'mobile';
    protected $returnType = 'array';
    protected $allowedFields = ['mobile', 'sms_status'];


    /**
     * @throws ReflectionException
     */
    public function updateMobiles()
    {
        $sub_query_mobile = $this->builder()->select('mobile');
        $student_model = new StudentModel();
        $mobiles = $student_model->select([
            'mobile'
        ])->distinct()
            ->whereNotIn('mobile', $sub_query_mobile)
            ->findAll();
        if (!empty($mobiles)) {
            $this->insertBatch($mobiles);
            $this->updateInvalidNumbers($mobiles);
            log_message('info', count($mobiles) . " new mobile numbers detected and updated");
        } else {
            log_message('notice', "No new mobile numbers detected and updated");
        }
    }

    public function getNewMobileCount()
    {
        return $this->select('mobile')
            ->where('sms_status', NULL, FALSE)
            ->countAllResults();
    }

    public function getNewMobileNumbers($limit = ''): array
    {
        return $this->select(['mobile'])
            ->where('sms_status', NULL, FALSE)
            ->findAll("$limit");
    }

    /**
     * @throws ReflectionException
     */
    function sendSmsToAllNewNumbers($limit = '10000')
    {
        helper('ews_sms_template');
        $count = 0;
        $total_mobile_count = $this->getNewMobileCount();
        while ($count < $total_mobile_count) {
            $mobile_numbers = $this->getNewMobileNumbers("$limit");
            if (bulk_helpline_promotion_sms($mobile_numbers) !== null) {
                $data = [];
                foreach ($mobile_numbers as $row) {
                    $data[] = [
                        'mobile' => $row['mobile'],
                        'sms_status' => 'SUBMITTED'
                    ];
                }
                $result = $this->updateSmsStatus($data);
                if ($result) {
                    log_message("info", $result . "  rows updated after sending of sms");
                } else {
                    log_message("error", "Could not update table after sending sms");
                }
            } else {
                log_message("error", "Could not send SMS.");
                break;
            }
            $count += count($mobile_numbers);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function updateSmsStatus($mobile_and_sms_status_data): bool
    {
        return $this->updateBatch($mobile_and_sms_status_data, 'mobile');
    }

    /**
     * @throws ReflectionException
     */
    private function updateInvalidNumbers($mobiles)
    {
        $invalid_numbers = [];
        foreach ($mobiles as $mobile) {
            if (strlen($mobile['mobile']) != 10) {
                $invalid_numbers [] = [
                    'mobile' => $mobile['mobile'],
                    'sms_status' => "INVALID",
                ];
            }
        }
        if (!empty($invalid_numbers))
            $this->updateBatch($invalid_numbers, 'mobile');
    }

    public function fetchVerifiedStatus($mobile_number): bool
    {
        //$verified = $this->select('sms_status')->find($mobile_number);
        return true;
        //return (!empty($verified) && $verified['sms_status'] == "DELIVERED");
    }
}

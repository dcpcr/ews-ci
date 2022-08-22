<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use ReflectionException;

class StudentModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'student';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['sms_status'];

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('general');
        helper('ews');
        helper('edutel');
    }

    public function updateStudents(string $file_name)
    {
        $school_ids = get_school_ids();
        $count = 0;
        $student_count = 0;
        $exists = false;
        foreach ($school_ids as $school_id) {
            $id = $school_id['id'];
            $data_array = fetch_student_data_for_school_from_edutel($id);
            if ($data_array) {
                for ($i = 0; $i < count($data_array); $i++) {
                    $data_array[$i]['school_id'] = $id;
                    $data_array[$i]['CorAddress'] = trim(preg_replace('/\s+/', ' ', $data_array[$i]['CorAddress']));;
                    $data_array[$i]['CorAddress'] = rtrim($data_array[$i]['CorAddress'], "\ ");
                    $student_count++;
                }
                if ($count != 0)
                    $exists = true;
                dump_array_in_file($data_array, $file_name, $exists);
                $count++;
            } else {
                log_message("notice", "Unable to fetch students for school - " . $id);
            }
        }
        if ($student_count > 0) {
            import_data_into_db($file_name, $this->DBGroup, $this->table);
            log_message("info", $student_count . " students imported.");
        } else {
            log_message("notice", "No students imported today!");
        }
    }

    public function getMobileOfNewStudents($limit = ''): array
    {
        return $this->select(['mobile'])
            ->distinct()
            ->where('sms_status', NULL, FALSE)
            ->where('length(mobile)=', '10')
            ->findAll("$limit");
    }

    public function getMobileNumbersToUpdateStatus(): array
    {
        return $this->select(['mobile'])
            ->distinct()
            ->where("sms_status", 'SUBMITTED')
            ->findAll();
    }

    public function getNewMobileCount()
    {
        return $this->distinct('mobile')
            ->where('sms_status', NULL, FALSE)
            ->countAllResults();
    }

    public function getStudentDetailsFormStudentTable($student_id)
    {
        return $this->select(['name', 'id', 'mobile', 'class', 'section'])
            ->find("$student_id");
    }

    /**
     * @throws ReflectionException
     */
    public function updateSmsStatus($mobile_and_sms_status_data): bool
    {
        return $this->updateBatch($mobile_and_sms_status_data, 'mobile');
    }

    /**
     * @throws ReflectionException
     */
    function sendSmsToAllNewStudents($limit = '10000')
    {
        helper('helpline_sms_template');
        $count = 0;
        $total_mobile_count = $this->getNewMobileCount();
        while ($count < $total_mobile_count) {
            $mobile_numbers = $this->getMobileOfNewStudents("$limit");
            $data = [];
            foreach ($mobile_numbers as $row) {
                $data[] = [
                    'mobile' => $row['mobile'],
                    'sms_status' => 'SUBMITTED'
                ];
            }
            bulk_helpline_promotion_sms($mobile_numbers);
            $this->updateSmsStatus($data);
            $count += count($mobile_numbers);
        }
    }

    /**
     * @throws ReflectionException
     */
    function updateSmsStatusOfMobileNumbers()
    {
        $mobile_numbers = $this->getMobileNumbersToUpdateStatus();
        $cdac_sms_status = new CdacSmsStatusModel();
        $sms_delivery_status = $cdac_sms_status->fetchLatestSmsStatusOf($mobile_numbers);
        if (!empty($sms_delivery_status)) {
            $sms_status_data = [];
            foreach ($sms_delivery_status as $row) {
                $sms_status_data[] = [
                    'mobile' => $row['mobile'],
                    'sms_status' => $row['status']
                ];
            }
            $this->updateSmsStatus($sms_status_data);
        }
    }
}

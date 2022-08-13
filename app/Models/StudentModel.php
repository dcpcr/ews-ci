<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class StudentModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'student';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

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

    /**
     * @throws \ReflectionException
     */
    public function getMobileForNewStudents(): array
    {
        return $this->select(['mobile'])->where('sms_status is NULL', NULL, FALSE)->findAll('');
    }

    public function getStudentsMobileNumber($limit = '', $offset = ''): array
    {
        return $this->select(['mobile'])->where("mobile !=", '')->findAll("$limit", "$offset");
    }

    public function getTotalStudentCount()
    {
        $count = $this->selectCount('id')->findAll();
        return $count[0]['id'];
    }

    public function getStudentDetailsFormStudentTable($student_id)
    {
        return $this->select(['name', 'id', 'mobile', 'class', 'section'])->find("$student_id");
    }

}

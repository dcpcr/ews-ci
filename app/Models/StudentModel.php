<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class StudentModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'student';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [];

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

    public function getSchoolWiseStudentCount(): array
    {
        $builder = $this->select(['school_id', 'count(distinct id) as count_total'])->groupBy('school_id');
        $query = $builder->get();
        return $query->getResultArray();
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
            for ($i = 0; $i < count($data_array); $i++) {
                $data_array[$i]['school_id'] = $id;
                $data_array[$i]['CorAddress'] = trim(preg_replace('/\s+/', ' ', $data_array[$i]['CorAddress']));;
                $student_count++;
            }
            if ($count != 0)
                $exists = true;
            dump_array_in_file($data_array, $file_name, $exists);
            $count++;
        }
        if ($student_count > 0) {
            import_data_into_db($file_name, $this->table);
            log_message("info", $student_count . " students imported.");
        } else {
            log_message("notice", "No students imported today!");
        }
    }

}

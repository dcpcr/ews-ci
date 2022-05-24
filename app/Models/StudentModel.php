<?php

namespace App\Models;

use CodeIgniter\Model;

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

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

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
        $exists = false;
        foreach ($school_ids as $id) {
            $data_array = fetch_student_data_for_school_from_edutel($id);
            for ($i = 0; $i < count($data_array); $i++) {
                $data_array[$i]['school_id'] = $id;
                $data_array[$i]['CorAddress'] = trim(preg_replace('/\s+/', ' ', $data_array[$i]['CorAddress']));;
            }
            if ($count != 0)
                $exists = true;
            dump_array_in_file($data_array, $file_name, $exists);
            $count++;
        }
        import_data_into_db($file_name, $this->table);
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentCountModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'student_count';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['school_id', 'class', 'total_student', 'district_name', 'zone_name', 'school_name'];

    /**
     * @throws \ReflectionException
     */
    public function updateStudentCount($data)
    {
        $this->truncate();
        $this->insertBatch($data, null, count($data));

    }

    public function getSchoolWiseStudentCount(array $school_ids, array $classes): array
    {
        return $this->select()
            ->whereIn("class", $classes)
            ->whereIn("school_id", $school_ids)
            ->findAll();

    }

    public function getSchoolIds($school_ids): array
    {
        $data = [];
        $res = $this->distinct()
            ->select(['school_id'])
            ->whereIn("school_id", $school_ids)
            ->findAll();
        foreach ($res as $row) {
            $data[] = $row['school_id'];
        }
        return $data;
    }


}

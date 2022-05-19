<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'attendance';
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

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getMarkedSchoolAttendance($start_date, $end_date, $school_id): array
    {
        $new_start = date("d/m/Y", strtotime($start_date));
        $new_end = date("d/m/Y", strtotime($end_date));
        $builder = $this->select(['count(*) as count_att', 'school_id', 'school.name as school_name'])->
        join('student', 'student.id = attendance.student_id')->
        join('school', 'school.id = student.school_id')->
        whereIn('student.school_id', $school_id)->
        where("date BETWEEN '$new_start' and  '$new_end' and attendance_status!='' ")->groupBy('school_id');
        $query = $builder->get();
        return $query->getResultArray();
    }


}

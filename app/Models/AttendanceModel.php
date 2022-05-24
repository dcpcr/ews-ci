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

    public function downloadAttendance(string $file_name, $from_date, $to_date)
    {
        $school_ids = get_school_ids();
        $count = 0;
        $exists = false;
        for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
            $school_count = 0;
            foreach ($school_ids as $id) {
                $final_array = array();
                $data_array = fetch_attendance_from_edutel($date->format("d/m/Y"), $id);
                if ($data_array) {
                    for ($i = 0; $i < count($data_array); $i++) {
                        if (trim($data_array[$i]['attendance']) != "") {
                            $data_array[$i]['date'] = $date->format("d/m/Y");
                            $final_array[$i]['student_id'] = $data_array[$i]['Student_ID'];
                            $final_array[$i]['attendance'] = $data_array[$i]['attendance'];
                            $final_array[$i]['date'] = $data_array[$i]['date'];
                        }
                    }
                    if ($count != 0)
                        $exists = true;
                    dump_array_in_file($final_array, $file_name, $exists);
                } else {
                    log_message("error", "No attendance fetched for date " . "$date->format('d/m/Y')");
                }
                $count++;
                $school_count += count($final_array);
            }
            log_message("info", $school_count . " attendance records fetched for date = " . $date->format("d/m/Y"));
        }
        import_data_into_db($file_name, $this->table);
    }

    public function getStudentAttendanceForLast30DaysFrom($student_id, $date): array
    {
        return $this->select("STR_TO_DATE(date,'%d/%m/%Y') as day", "attendance_status")
            ->where("student_id = '$student_id' and 
            STR_TO_DATE(date,'%d/%m/%Y') <= STR_TO_DATE('" . $date->format("d/m/Y") . "','%d/%m/%Y')")
            ->findAll(30);
    }


}

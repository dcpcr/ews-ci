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
                    $data_array[$i]['CorAddress'] = remove_slash_from_string($data_array[$i]['CorAddress']);
                    $data_array[$i]['FatherName'] = remove_slash_from_string($data_array[$i]['FatherName']);
                    $data_array[$i]['MotherName'] = remove_slash_from_string($data_array[$i]['MotherName']);
                    $data_array[$i]['Student_Name'] = remove_slash_from_string($data_array[$i]['Student_Name']);
                    $data_array[$i]['GuardianName'] = remove_slash_from_string($data_array[$i]['GuardianName']);
                    $data_array[$i]['GuardianRelation'] = remove_slash_from_string($data_array[$i]['GuardianRelation']);
                    $data_array[$i]['District_Name'] = remove_slash_from_string($data_array[$i]['District_Name']);
                    $data_array[$i]['CWSN'] = remove_slash_from_string($data_array[$i]['CWSN']);
                    $data_array[$i]['Type_OF_Disability'] = remove_slash_from_string($data_array[$i]['Type_OF_Disability']);
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

    public function getStudentDetailsFormStudentTable($student_id)
    {
        return $this->select(['name', 'id', 'mobile', 'class', 'section'])
            ->find("$student_id");
    }

    public function getClassWiseStudentCount($school_ids): array
    {
        return $this->distinct()
            ->select(["school_id", "class", "count(*) as count"])
            ->whereIn("school_id", $school_ids)
            ->groupBy("class")
            ->findAll();
    }

    public function getStudentCountFor(array $school_ids, array $classes, string $group_by): array
    {
        return $this->select([
            'count(distinct(student.id)) as count_total',
            "$group_by"
        ])
            ->whereIn('school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->groupBy("$group_by")
            ->findAll();
    }

    /**
     * @throws \ReflectionException
     */
    public function prepareSchoolWiseTotalStudents()
    {
        $res = $this->select(['count(student.id) as total_student', 'student.school_id', 'student.class as class', 'district.name as district_name', 'zone.name as zone_name', 'school.name as school_name'])
            ->join('school', 'school.id = student.school_id')
            ->join('school_mapping', 'school_mapping.school_id = student.school_id')
            ->join('district', 'district.id = school_mapping.district_id')
            ->join('zone', 'zone.id = school_mapping.zone_id')
            ->groupBy("student.school_id")
            ->findAll();
        $student_count_model = new StudentCountModel();
        $student_count_model->updateStudentCount($res);
    }

    public function fetchStudents()
    {
        $school_ids = get_school_ids();
        foreach ($school_ids as $school_id) {
            $id = $school_id['id'];
            $data_array = fetch_attendance_in_json_file_from_edudel($id);
            log_message("info", "data fetched for school_id:" . $id);
        }
    }

}

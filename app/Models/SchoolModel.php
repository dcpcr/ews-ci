<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class SchoolModel extends Model
{
    protected $DBGroup = 'master';
    protected $table = 'school';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $returnType = 'array';
    protected $allowedFields = ['id', 'name'];

    //TODO: add created and updated fields to the school table

    public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        helper('edutel');
        helper('general');
    }

    public function updateSchools($file_name)
    {
        $response_array = fetch_schools_from_edutel();
        if ($response_array) {
            dump_array_in_file($response_array, $file_name, false);
            import_data_into_db($file_name, $this->DBGroup, $this->table); //TODO: This can possibly just iterate and update the model since the table length is not much
            log_message("info", count($response_array) . " schools scraped today");
        } else {
            log_message("notice", "No schools could be scraped today!!");
        }
    }
    public function getSchoolWiseStudentCount($school_ids, $classes): array
    {
        $sub_query = $this->select([
            'count(distinct(student.id)) as count_total',
            'school.id as school_id'
        ])
            ->join('student', 'school.id = student.school_id')
            ->whereIn('school.id', $school_ids)
            ->whereIn('student.class', $classes)
            ->groupBy('school.id')
            ->getCompiledSelect();

        $builder = $this->select([
            'count_total',
            'school.id as school_id','district.name as district_name','zone.name as zone_name'
        ])
            ->join('(' . $sub_query . ') `s1`', 'school.id = s1.school_id', 'left')
            ->join('school_mapping', 'school_mapping.school_id = school.id')
            ->join('district', 'district.id = school_mapping.district_id')
            ->join('zone', 'zone.id = school_mapping.zone_id')
            ->whereIn('school.id', $school_ids)
            ->orderBy('school.id');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getMarkedSchoolAttendance($school_ids, $classes, $start, $end): array
    {
        helper('general');
        $ews_db= get_database_name_from_db_group('default');
        $sub_query = $this->select([
            'count(distinct(student.id)) as count_att',
            'school.id as school_id'
        ])
            ->join('student', 'school.id = student.school_id' )
            ->join($ews_db.'.attendance as attendance', 'student.id = attendance.student_id')
            ->whereIn('school.id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("STR_TO_DATE(attendance.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->groupBy('school.id')
            ->getCompiledSelect();

        $builder = $this->select([
            'count_att',
            'school.id as school_id',
            'school.name as school_name'
        ])
            ->join('(' . $sub_query . ') `s1`', 'school.id = s1.school_id', 'left')
            ->whereIn('school.id', $school_ids)
            ->orderBy('school.id');
        $query = $builder->get();
        return $query->getResultArray();
    }


}
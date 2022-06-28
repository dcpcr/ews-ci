<?php

namespace App\Models;

use CodeIgniter\Model;

class CaseReasonModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'case_reason';
    protected $returnType       = 'array';

    function getReasonsMaleCount(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db= get_database_name_from_db_group('master');
        return $this->select(['reason_name','count(*) as count'])
            ->join('reason', 'reason.reason_id=case_reason.reason_id')
            ->join('detected_case', 'detected_case.id=case_reason.case_id')
            ->join($master_db.'.student as student', 'student.id = detected_case.student_id')
            ->join($master_db.'.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='Male'")
            ->groupBy('reason_name')
            ->orderBy('reason_name')
            ->findAll();

    }
    function getReasonsFemaleCount(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db= get_database_name_from_db_group('master');
        return $this->select(['reason_name','count(*) as count'])
            ->join('reason', 'reason.reason_id=case_reason.reason_id')
            ->join('detected_case', 'detected_case.id=case_reason.case_id')
            ->join($master_db.'.student as student', 'student.id = detected_case.student_id')
            ->join($master_db.'.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='Female'")
            ->groupBy('reason_name')
            ->orderBy('reason_name')
            ->findAll();

    }
    function getReasonsTransgenderCount(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db= get_database_name_from_db_group('master');
        return $this->select(['reason_name','count(*) as count'])
            ->join('reason', 'reason.reason_id=case_reason.reason_id')
            ->join('detected_case', 'detected_case.id=case_reason.case_id')
            ->join($master_db.'.student as student', 'student.id = detected_case.student_id')
            ->join($master_db.'.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y') and master.student.gender='Transgender'")
            ->groupBy('reason_name')
            ->orderBy('reason_name')
            ->findAll();

    }
}

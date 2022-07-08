<?php

namespace App\Models;

use CodeIgniter\Model;

class CallDispositionModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'call_disposition';
    protected $primaryKey = 'call_id';
    protected $returnType = 'array';

    function getCallDisposition(array $school_ids, $classes, $start, $end): array
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['disposition_type', 'count(*) as count'])
            ->join('detected_case', 'detected_case.id=call_disposition.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" . $end . "', '%m/%d/%Y')")
            ->groupBy('disposition_type')
            ->findAll();
    }
}

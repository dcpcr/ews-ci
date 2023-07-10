<?php

namespace App\Models;

use CodeIgniter\Model;

class DetectedCaseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'detected_case';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];


    public function getTotalNumberOfDetectedStudentsFor($school_id, $classes, $start_date, $end_date)
    {

        return $this->distinct()->select("student_id")
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->countAllResults();

    }

    public function getTotalListOfDetectedStudentsFor(array $school_id, array $classes, $start_date, $end_date): array
    {
        return $this->select()
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();
    }
}

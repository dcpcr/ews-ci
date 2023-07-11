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

    public function getBTSCaseCount(array $school_id, array $classes, $start_date, $end_date, $without_intervention = false)
    {
        $intervention_count = $this->distinct()
            ->select("student_id")
            ->join("call_disposition", "case_id=id", "left")
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("call_disposition_id", "1")
            ->where("status", "Back to School")
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->countAllResults();

        if ($without_intervention) {
            return $this->distinct()
                    ->select("student_id")
                    ->whereIn('student_school_id', $school_id)
                    ->whereIn('student_class', $classes)
                    ->where("status", "Back to School")
                    ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                        $end_date . "', '%m/%d/%Y')")
                    ->countAllResults() - $intervention_count;
        }
        return $intervention_count;

    }

    public function getBTSList(array $school_id, array $classes, $start_date, $end_date, bool $without_ews_intervention = false): array
    {
        if ($without_ews_intervention) {
            $with_intervention_student_ids = $this->select("student_id")
                ->join("call_disposition", "case_id=id", "left")
                ->whereIn('student_school_id', $school_id)
                ->whereIn('student_class', $classes)
                ->where("call_disposition_id", "1")
                ->where("status", "Back to School")
                ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                    $end_date . "', '%m/%d/%Y')")
                ->groupBy("student_id")
                ->findAll();
            $student_ids = [];
            foreach ($with_intervention_student_ids as $id) {
                $student_ids[] = $id['student_id'];
            }

            return $this->select()
                ->join("call_disposition", "case_id=id", "left")
                ->whereIn('student_school_id', $school_id)
                ->whereIn('student_class', $classes)
                ->whereNotIn('student_id', $student_ids)
                ->where("status", "Back to School")
                ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                    $end_date . "', '%m/%d/%Y')")
                ->groupBy("student_id")
                ->findAll();


        }
        return $this->select()
            ->join("call_disposition", "case_id=id", "left")
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("call_disposition_id", "1")
            ->where("status", "Back to School")
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();

    }

    public function getYetToBeBroughtBackToSchoolByCall(array $school_id, array $classes, $start_date, $end_date): array
    {
        return $this->select()
            ->join("call_disposition", "case_id=id", "left")
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("call_disposition_id", "1")
            ->where("status", "Fresh")
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();
    }

    public function getYetToBeBroughtBackToSchoolBySMS(array $school_id, array $classes, $start_date, $end_date): array
    {
        return $this->select()
            ->join("master.mobile_sms_status as t1", "t1.mobile=student_mobile", "left")
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("sms_status", "Delivered")
            ->where("status", "Fresh")
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();

    }

    public function getCorporalPunishmentListFor(array $school_id, array $classes, $start_date, $end_date): array
    {
        return $this->select()
            ->join("reason_for_absenteeism" ,"case_id=id")
            ->whereIn("reason_id",['8'])
            ->whereIn('student_school_id', $school_id)
            ->whereIn('student_class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start_date . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end_date . "', '%m/%d/%Y')")
            ->groupBy("student_id")
            ->findAll();
    }
}

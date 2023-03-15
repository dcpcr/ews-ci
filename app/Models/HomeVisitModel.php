<?php

namespace App\Models;

use DateTimeInterface;

class HomeVisitModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'home_visit';
    protected $primaryKey = 'case_id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id'];

    public function getHomeVisitCount(array $school_ids, array $classes, $start, $end)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select(['case_id'])
            ->join('detected_case', 'detected_case.id=home_visit.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("detected_case.status!=","Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();
    }

    public function getHomeVisitList(array $school_ids, array $classes, $start, $end,$student_ids)
    {
        helper('general');
        $master_db = get_database_name_from_db_group('master');
        return $this->select()
            ->join('detected_case', 'detected_case.id=home_visit.case_id')
            ->join($master_db . '.student as student', 'student.id = detected_case.student_id')
            ->join($master_db . '.school as school', 'student.school_id = school.id')
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->whereNotIn('student.id',[$student_ids])
            ->where("detected_case.status!=","Back To School")
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->findAll();
    }

    protected function getKeys(): array
    {
        return array('case_id');
    }

    protected function getKeyMappings(): array
    {
        return array(
            "case_id" => "case_id"
        );
    }

    /**
     * @throws \ReflectionException
     */

    public function updateHomeVisitData(DateTimeInterface $from_date, DateTimeInterface $to_date)
    {
        helper('cyfuture');
        $url = get_cyfuture_home_visit_url();
        $record_count = download_and_process_cyfuture_api_data($url, $from_date->format("Y-m-d"),
            $to_date->format("Y-m-d"), function ($records, $page_number) use ($url) {
                if ($records) {
                    $this->updateCaseDetails($records, true);
                    log_message("info", "The Cyfuture EWS Home Visit API call success, for Page - " . $page_number);
                } else {
                    log_message("error", "The Cyfuture EWS Home Visit  record API call failed, Page -" . $page_number . "url - " . $url);
                }
            }
        );
        log_message("info", "Total Records fetched from Cyfuture EWS Home Visit  record API, ->" . $record_count);
    }

}

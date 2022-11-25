<?php

namespace App\Models;

use DateTimeInterface;

class YetToBeContactedModel extends CaseDetailsModel
{
    protected $DBGroup = 'default';
    protected $table = 'yet_to_be_taken_up';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['case_id'];


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

    public function updateYetToBeTakenUpData(DateTimeInterface $from_date, DateTimeInterface $to_date)
    {
        helper('cyfuture');
        $url = get_cyfuture_yettobetakenup_url();
        $record_count = download_and_process_cyfuture_api_data($url, $from_date->format("Y-m-d"),
            $to_date->format("Y-m-d"), function ($records, $page_number) use ($url) {
                if ($records) {
                    $this->updateCaseDetails($records, true);
                    log_message("info", "The Cyfuture EWS Yet To Be Taken Up API call success, for Page - " . $page_number);
                } else {
                    log_message("error", "The Cyfuture EWS Yet To Be Taken Up API call failed, Page -" . $page_number . "url - " . $url);
                }
            }
        );
        log_message("info", "Total Records fetched from Cyfuture EWS Yet To Be Taken Up  record API, ->" . $record_count);
    }

    public function getYetToBeContactedCaseCount(array $school_ids, array $classes, $start, $end)
    {

        return $this->select(['case_id'])
            ->join("master.student as student", "student.id=student_id")
            ->whereIn('student.school_id', $school_ids)
            ->whereIn('student.class', $classes)
            ->where("day BETWEEN STR_TO_DATE('" . $start . "' , '%m/%d/%Y') and STR_TO_DATE('" .
                $end . "', '%m/%d/%Y')")
            ->countAllResults();

    }


}

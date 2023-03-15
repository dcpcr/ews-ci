<?= 
    $this->include('dashboard/partial/scripts/data-table-script'); 

    $counter = 0;
    $table_data = [];
    var_dump($response['yet_to_be_contacted']);
    die();
    foreach (
        //$response['back_to_school'] as $case
        $response['yet_to_be_contacted'] as $case
        ) {
        $table_data [] = [
            "serial_no" => ++$counter,
            "case_id" => $case['case_id'],
            "student_id" => $case['student_id'],
            "status" => $case['status'],
            "district" => $case['district'],
            "id" => $case['id'],
            "name" => $case['name'],
            "dob" => $case['dob'],
            "class" => $case['class'],
            "section" => $case['section'],
            "gender" => $case['gender'],
            "father" => $case['father'],
            "mother" => $case['mother'],
            "mobile" => $case['mobile'],
            "address" => $case['address'],
            "school_id" => $case['school_id'],
            "created_at" => $case['created_at'],
            "updated_at" => $case['updated_at'],
            "seven_days_criteria" => $case['seven_days_criteria'],
            "thirty_days_criteria" => $case['thirty_days_criteria'],
            "date_of_bts" => $case['date_of_bts'],
            "system_bts" => $case['system_bts'],
            "priority" => $case['priority'],
            "day" => $case['day']
        ];
    }


?>

<script>
    const summaryBackToSchoolList = <?=json_encode($table_data)?>;
</script>

<script src='/assets-adminlte/ews-js/summary-page-list.js'></script>
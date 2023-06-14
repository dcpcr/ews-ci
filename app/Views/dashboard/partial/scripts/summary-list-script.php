<?=
$this->include('dashboard/partial/scripts/data-table-script');
$data = "";
if (isset($response['yet_to_be_contacted'])) {
    $data = $response['yet_to_be_contacted'];
} elseif (isset($response['back_to_school'])) {
    $data = $response['back_to_school'];
}elseif (isset($response['dropped_out_in_contact'])) {
    $data = $response['dropped_out_in_contact'];
}elseif (isset($response['moved_out_of_delhi'])) {
    $data = $response['moved_out_of_delhi'];
}elseif (isset($response['enrolled_in_contact'])) {
    $data = $response['enrolled_in_contact'];
}elseif (isset($response['contact_not_established'])) {
    $data = $response['contact_not_established'];
}
elseif (isset($response['reason_for_absenteeism'])){
    $data = $response['reason_for_absenteeism'];
}
elseif (isset($response['changed_school'])){
    $data = $response['changed_school'];
}
$counter = 0;
$table_data = [];
if (!empty($data)) {
    foreach ($data as $case) {
        $table_data [] = [
            "serial_no" => ++$counter,
            "case_id" => $case['case_id'],
            "student_id" => $case['student_id'],
            "status" => isset($status)?$status:$case['status'],
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

}


?>

<script>
    const summaryBackToSchoolList = <?=json_encode($table_data)?>;
</script>

<script src='/assets-adminlte/ews-js/summary-page-list.js'></script>
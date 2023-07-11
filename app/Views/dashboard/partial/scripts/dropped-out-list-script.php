<?=
$this->include('dashboard/partial/scripts/data-table-script');
$data = "";
$counter = 0;
$table_data = [];
if (isset($response['dropped_out_list'])) {
    $data = $response['dropped_out_list'];
    if (!empty($data)) {
        foreach ($data as $case) {
            $table_data [] = [
                "serial_no" => ++$counter,
                "student_id" => $case['student_id'],
                "status" => isset($status) ? $status : $case['status'],
                "student_name" => $case['student_name'],
                "dob" => $case['student_dob'],
                "class" => $case['student_class'],
                "section" => $case['student_section'],
                "gender" => $case['student_gender'],
                "father" => $case['student_father'],
                "mother" => $case['student_mother'],
                "mobile" => $case['student_mobile'],
                "address" => $case['student_address'],
                "school_id" => $case['student_school_id'],
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

}
?>

<script>
    const wrongMobileNumberListData = <?=json_encode($table_data)?>;
</script>

<script src='/assets-adminlte/ews-js/corporal-punishment-list.js'></script>
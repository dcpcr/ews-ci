<?=
$this->include('dashboard/partial/scripts/data-table-script');
$data = "";
$counter = 0;
$table_data = [];
if (isset($response['wrong_mobile_number_list'])) {
    $data = $response['wrong_mobile_number_list'];
    if (!empty($data)) {
        foreach ($data as $case) {
            $mobile = ($case['mobile'] == '') ? 'NA' : $case['mobile'];
            $table_data [] = [
                "serial_no" => ++$counter,
                "student_id" => $case['id'],
                "mobile" => $mobile,
                "district" => $case['district'],
                "student_name" => $case['name'],
                "dob" => $case['dob'],
                "class" => $case['class'],
                "section" => $case['section'],
                "gender" => $case['gender'],
                "father" => $case['father'],
                "mother" => $case['mother'],
                "address" => $case['address'],
                "school_id" => $case['school_id'],
            ];
        }
    }

}
?>

<script>
    const wrongMobileNumberListData = <?=json_encode($table_data)?>;
</script>

<script src='/assets-adminlte/ews-js/wrong-mobile-number-list.js'></script>
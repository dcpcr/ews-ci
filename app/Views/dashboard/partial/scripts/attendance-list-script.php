<?=
$this->include('dashboard/partial/scripts/data-table-script');
$data = "";
$counter = 0;
$table_data = [];
if (isset($response['marked_attendance_student_list'])) {
    $data = $response['marked_attendance_student_list'];
    if (!empty($data)) {
        foreach ($data as $case) {
            $table_data [] = [
                "serial_no" => ++$counter,
                "student_id" => $case['id'],
                "attendance_status" => $case['attendance_status'],
                "district" => $case['district'],
                "id" => $case['id'],
                "student_name" => $case['name'],
                "dob" => $case['dob'],
                "class" => $case['class'],
                "section" => $case['section'],
                "gender" => $case['gender'],
                "father" => $case['father'],
                "mother" => $case['mother'],
                "mobile" => $case['mobile'],
                "address" => $case['address'],
                "school_id" => $case['school_id'],
            ];
        }
    }

}
?>

<script>
    const homePageAttendanceListData = <?=json_encode($table_data)?>;
</script>

<script src='/assets-adminlte/ews-js/home-page-attendance-list.js'></script>
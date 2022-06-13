<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<?php
$counter = 0;
$att_table = '';
$schoolWiseStudentCount = $response['schoolWiseStudentCount'];
$markedAttendanceCount = $response['markedAttendanceCount'];
$school_data = [];
foreach ($markedAttendanceCount as $school) {
    $school_data [$school['school_id']] ['school_name'] = $school['school_name'];
    $school_data [$school['school_id']] ['school_id'] = $school['school_id'];
    $school_data [$school['school_id']] ['attendance_count'] = is_null($school['count_att']) ? 0 : $school['count_att'];
}
foreach ($schoolWiseStudentCount as $school) {
    $school_data [$school['school_id']] ['student_count'] = is_null($school['count_total']) ? 0 : $school['count_total'];
}
$table_data = [];
$count = 1;
foreach ($school_data as $school_id => $school) {
    //prepare table data
    $table_data [] = [
        "Serial_no" => $count++,
        "School" => $school_id . " - " . $school['school_name'],
        "Total_Students" => $school['student_count'],
        "Attendance_Marked" => $school['attendance_count']
    ];
}
?>
<script>
    const attendancedata = <?=$data=json_encode($table_data)?>;
</script>
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
    $school_data [$school['school_id']] ['average_count'] = is_null($school['avg_att']) ? 0 : $school['avg_att'];
    $school_data [$school['school_id']] ['marked_days_count'] = is_null($school['days_att']) ? 0 : $school['days_att'];
}
foreach ($schoolWiseStudentCount as $school) {
    $school_data [$school['school_id']] ['student_count'] = is_null($school['count_total']) ? 0 : $school['count_total'];
    $school_data [$school['school_id']] ['zone_name'] = $school['zone_name'];
    $school_data [$school['school_id']] ['district_name'] = $school['district_name'];
}
$table_data = [];
$count = 1;
foreach ($school_data as $school_id => $school) {
    //prepare table data
    $table_data [] = [
        "Serial_no" => $count++,
        "School" => $school_id . " - " . $school['school_name'],
        "Total_Students" => $school['student_count'],
        "District" => $school['district_name'],
        "Zone" => $school['zone_name'],
        "Attendance_Marked" => $school['attendance_count'],
        "Average_Attendance_Marked" => $school['average_count'],
        "Attendance_Marked_Days" => $school['marked_days_count'],
    ];
}
?>
<script>
    const attendancedata = <?=$data = json_encode($table_data)?>;
</script>
<script src='/assets-adminlte/ews-js/attendance.js'></script>
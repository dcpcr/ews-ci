<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<?php
$attendanceCountData = $attendanceLabel = $totalStudentCountData = "";
foreach ($response['attendance_data_day_wise'] as $row) {
    $attendanceLabel .= "'" . $row['date'] . "',";
    $attendanceCountData .= "'" . $row['attendance_count'] . "',";
    $totalStudentCountData .= "'" . $row['total_student'] . "',";
}
$attendanceLabel = substr_replace($attendanceLabel, "", -1) . "";
$attendanceCountData = substr_replace($attendanceCountData, "", -1) . "";

$attendance_data = $response['attendance_data_for_graph'];
$graphlable=ucfirst($response['graph_lable']).' Wise Attendance %';
$lable = $data = '';
foreach ($attendance_data as $row) {
    $lable .= "'" . $row[$response['graph_lable']] . "',";
    $data .= "'" . $row['Attendance_Marked_Percent'] . "',";
}
$lable = substr_replace($lable, "", -1) . "";
$data = substr_replace($data, "", -1) . "";
?>

<script>
    const column = "<?=$response['col_name']?>";
    const lablevalue = [<?=$lable?>];
    const graph_legend = "<?=$graphlable?>";
    const labledata = [<?=$data?>];
    const attendancedata = <?=$data = json_encode($attendance_data)?>;
    const attendanceLabel = [<?=$attendanceLabel?>];
    const attendanceCountData = [<?=$attendanceCountData?>];
    const totalStudentCountData = [<?=$totalStudentCountData?>];
</script>
<script src='/assets-adminlte/ews-js/online-attendance-home-page.js'></script>
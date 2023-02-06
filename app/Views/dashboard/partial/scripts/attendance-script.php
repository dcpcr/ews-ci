<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<script>
    const attendancedata = <?=$data = json_encode($response['attendance_data'])?>;
</script>
<script src='/assets-adminlte/ews-js/attendance.js'></script>
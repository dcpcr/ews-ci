<?php
$counter = 0;
$att_table = '';
$schoolWiseStudentCount = $response['schoolWiseStudentCount'];
$markedAttendanceCount = $response['markedAttendanceCount'];

$school_data = [];
foreach ($markedAttendanceCount as $school) {
    $school_data [$school['school_id']] ['school_name'] = $school['school_name'];
    $school_data [$school['school_id']] ['attendance_count'] = is_null($school['count_att']) ? 0 : $school['count_att'];
}
foreach ($schoolWiseStudentCount as $school) {
    $school_data [$school['school_id']] ['student_count'] = is_null($school['count_total']) ? 0 : $school['count_total'];
}

foreach ($school_data as $school_id => $school) {
    //prepare table data
    $att_table .= "<tr><td>" . ++$counter . "</td><td>" . $school_id . ' - ' . $school['school_name'] . "</td><td>"
        . $school['student_count'] . "</td><td>" . $school['attendance_count'] . "</td>";
}
?>
<div class="row">
    <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h4 class="p-3">Marked Attendance School List</h4>
            </div>
            <div class="card-body">
                <table id="attendancetable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>School</th>
                        <th>Total Students</th>
                        <th>Attendance Marked</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?= $att_table ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- ./card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->



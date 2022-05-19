<?php
$counter = 0;
$att_table = '';
$schoolWiseStudentCount = $response['schoolWiseStudentCount'];
$markedAttendanceCount = $response['markedAttendanceCount'];

foreach ($schoolWiseStudentCount as $school) {
    foreach ($markedAttendanceCount as $schoolAttendance) {
        //prepare table data
        if ($school['school_id'] == $schoolAttendance['school_id']) {
            $att_table .= "<tr><td>" . ++$counter . "</td><td>" . $school['school_id'] . ' - ' . $schoolAttendance['school_name'] . "</td><td>" . $school['count_total'] . "</td><td>" . $schoolAttendance['count_att'] . "</td>";

        }


    }
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>School</th>
                        <th>Total Student</th>
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



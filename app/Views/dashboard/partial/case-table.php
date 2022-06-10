<?php
$case_table = '';
foreach ($response as $row) {
    //prepare table data
    $criteria = ($row['detection_criteria'] == '7 Consecutive Days Absent') ? "7 Consecutive Days" : "20/30 days";
    $case_table .= "<tr><td>" . $row['case_id'] .
        "</td><td>" . $row['day'] .
        "</td><td>" . $row['status'] .
        "</td><td>" . $row['student_id'] . ' - ' . $row['student_name'] .
        "</td><td>" . $row['school_id'] . ' - ' . $row['school_name'] .
        "</td><td>" . $row['class'] . " - " . $row['section'] .
        "</td><td>" . $row['gender'] .
        "</td><td>" . $row['dob'] .
        "</td><td>" . $row['father'] .
        "</td><td>" . $row['mother'] .
        "</td><td>" . $row['mobile'] .
        "</td><td>" . $row['address'] .
        "</td><td>" . $row['district'] .
        "</td><td>" . $row['zone'] .
        "</td><td>" . $criteria .
        "</td></tr>";
}
?>

<div class="row">
    <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
            <div class="card-header d-flex p-0">
                <h4 class="p-3">Case Details</h4>
            </div>
            <div class="card-body">
                <table id="casetable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Case_Id</th>
                        <th>Detection Date</th>
                        <th>Status</th>
                        <th>Student</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Gender</th>
                        <th>DoB</th>
                        <th>Father</th>
                        <th>Mother</th>
                        <th>Contact No.</th>
                        <th>Address</th>
                        <th>District</th>
                        <th>Zone</th>
                        <th>Detection Criteria</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?= $case_table ?>
                    </tbody>
                </table>
            </div>

        </div>
        <!-- ./card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
		
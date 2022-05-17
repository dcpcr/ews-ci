<?php
$counter=0;
$case_table='';
    foreach ($data as $row){

    //prepare table data
    $case_table.="<tr><td>".$row['case_id']."</td><td>".$row['school_id']."</td><td>".$row['student_id']."</td><td>".$row['class']."</td><td>".$row['detection_criteria']."</td><td>".$row['status']."</td></tr>";
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Case_Id</th>
                        <th>School_Id</th>
                        <th>Student_Id</th>
                        <th>Class</th>
                        <th>Detection Criteria</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$case_table?>
                    </tbody>
                </table>
            </div>

        </div>
        <!-- ./card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
		
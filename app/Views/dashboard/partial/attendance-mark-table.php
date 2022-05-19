<?php
$counter = 0;
$att_table = '';
foreach($total_student as $aV){
    $aTmp1[] = $aV['school_id'];
}

foreach($total_attendance as $aV){
    $aTmp2[] = $aV['school_id'];
}

//$new_array = array_diff($aTmp1,$aTmp2);
//$result = array_diff($total_student, $total_attendance);
//var_dump($new_array);

foreach ($total_student as $row) {

    foreach ($total_attendance as $att) {
        //prepare table data
        if ($row['school_id'] == $att['school_id']) {
            $att_table .= "<tr><td>" . ++$counter . "</td><td>" . $row['school_id'] . ' - ' . $att['school_name'] . "</td><td>" . $row['count_total'] . "</td><td>" . $att['count_att'] . "</td>";

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



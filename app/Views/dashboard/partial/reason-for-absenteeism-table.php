<?php
$grievance_data = [];

foreach ($response['sub_division_wise_total_dcpcr_helpline_case_count'] as $row) {
    $grievance_data [$row['sub_division']] ['sub_division'] = $row['sub_division'];
    $grievance_data [$row['sub_division']] ['total_ticket_count'] = is_null($row['total_ticket_count']) ? 0 : $row['total_ticket_count'];
}
foreach ($response['sub_division_wise_in_total_progress_dcpcr_helpline_case_count'] as $row) {
    $grievance_data [$row['sub_division']] ['total_in_progress_ticket_count'] = is_null($row['total_in_progress_ticket_count']) ? 0 : $row['total_in_progress_ticket_count'];
    $grievance_data [$row['sub_division']] ['closed'] = is_null($row['total_in_progress_ticket_count'] && $grievance_data [$row['sub_division']]['total_ticket_count']) ? 0 : $grievance_data [$row['sub_division']]['total_ticket_count'] - $row['total_in_progress_ticket_count'];
}
?>
<div class="card">
    <div>
        <p>Reason for Absenteeism Stated by the student's family</p>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>S.No</th>
                <th>Reason</th>
                <th>% of Total Cases</th>
                <th>Number of Cases</th>
                <th>Action to be taken for Prevention</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 0;
            foreach ($response['reason_wise_case_count'] as $row) {
                ?>
                <tr>
                    <td><?= ++$counter ?></td>
                    <td><?= $row['reason_name'] ?></td>

                    <td class="project_progress">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="57" aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="width: <?=
                                 ($response['total_detected_cases'] == 0) ? $percent = 0 : $percent = round($row['count'] / $response['total_detected_cases'] * 100, 2); ?>%">
                            </div>
                        </div>
                        <small>
                            <?= $percent ?>%
                        </small>
                    </td>
                    <td><?php
                        if($row['id'] != 4 && $row['id'] != 5 && $row['id'] != 8){
                            echo "<a href = '".$row['id']."'>".$row['count']."</a>";
                        } 
                        else{
                            echo $row['count'];
                        }       
                    ?>
                    </td>
                    <td><?= $row['action_taken'] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>


<div class="card">
    <div>

        <h5>Grievances Registered with DCPCR</h5>
        <p>Undertake intervention at a school-level to prevent the below mentioned grievances from happening</p>

    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>S.No</th>
                <th>Grievance Registered</th>
                <th>In Progress</th>
                <th>Closed</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 0;
            foreach ($grievance_data as $row) {
                ?>
                <tr>
                    <td><?= ++$counter ?></td>
                    <td><?= $row['sub_division'] ?></td>
                    <td><?= $row['total_in_progress_ticket_count'] ?></td>
                    <td><?= $row['closed'] ?></td>
                    <td><?= $row['total_ticket_count'] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>









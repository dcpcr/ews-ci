<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">

                    Call <i class="fas fa-small fa-phone"></i> +91-9311551393 : DCPCR helpline for any child protection related support required (Timing: 9:30
                    AM to 11:00 PM )


                </h3>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill"
                           href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home"
                           aria-selected="true">View Reason for Absenteeism</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill"
                           href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile"
                           aria-selected="false">View frequent absenteeism</a>
                    </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                         aria-labelledby="custom-content-below-home-tab">

                        <?php
                        $grievance_data = [];

                        foreach ($response['sub_division_wise_total_dcpcr_helpline_case_count'] as $row) {
                            $grievance_data [$row['sub_division']] ['sub_division'] = $row['sub_division'];
                            $grievance_data [$row['sub_division']] ['total_in_progress_ticket_count'] = 0;
                            $grievance_data [$row['sub_division']] ['closed'] = 0;
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
                                                if ($row['id'] != 4 && $row['id'] != 5 && $row['id'] != 8) {
                                                    echo "<a href = '" . $row['id'] . '/' . str_replace("/", "*", $row['reason_name']) . "'>" . $row['count'] . "</a>";
                                                } else {
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
                    </div>
                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel"
                         aria-labelledby="custom-content-below-profile-tab">
                        Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut
                        ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing
                        elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                        Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus
                        ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc
                        euismod pellentesque diam.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
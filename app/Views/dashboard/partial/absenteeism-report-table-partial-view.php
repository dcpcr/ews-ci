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
                                    foreach ($response['death_data'] as $row) {
                                    ?>
                                    <tr>
                                            <td><?= ++$counter ?></td>
                                    <td><?= $row['who_passed_away'] ?></td>

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
                                            echo "<a href = '" . $row['id'] . '/' . str_replace(" ", "_", $row['who_passed_away']) . "'>" . $row['count'] . "</a>";
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
                                    <?php
                                    foreach ($response['sickness_data'] as $row) {
                                    ?>
                                    <tr>
                                        <td><?= ++$counter ?></td>
                                        <td><?= $row['who_is_suffering'] ?></td>

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
                                                echo "<a href = '" . $row['id'] . '/' . str_replace(" ", "_", $row['who_is_suffering']) . "'>" . $row['count'] . "</a>";
                                            } else {
                                                echo $row['count'];
                                            }
                                            ?>
                                        </td>
                                        <td><?= $row['action_taken'] ?></td>
                                    </tr>

                                    <?php
                                    }
                                    foreach ($response['other_reason_wise_case_count'] as $row) {
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
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="summaryTable" class="table table-bordered table-striped">
                                            <thead>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
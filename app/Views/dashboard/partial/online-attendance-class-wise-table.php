<div class="card">
    <div>
        <h3 class="card-title">Class Wise Attendance Performance for Last Marked Attendance
            Date: <?= $response['latest_marked_attendance_date'][0]['date'] ?></h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>S.No</th>
                <th>Class</th>
                <th>% Online attendance marked (Last Attendance Mark)</th>
                <th>Attendance Marked</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($response['marked_attendance_data'] as $row) {

                ?>
                <tr>
                    <td><?= $row['Serial_no'] ?></td>
                    <td><?= $row['Class'] ?></td>
                    <td class="project_progress">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="7" aria-valuemin="0"
                                 aria-valuemax="100" style="width: <?= $row['Attendance_Marked_Percent'] ?>%">
                            </div>
                        </div>
                        <small>
                            <?= $row['Attendance_Marked_Percent'] ?>% Attendance Marked
                        </small>
                    </td>
                    <td><?= $row['Attendance_Marked'] ?>/<?= $row['Total_Students'] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>


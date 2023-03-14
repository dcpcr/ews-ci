<div class="row">
    <div class="col-md-12 col-sm-6">
        <div class="card">
            <div>
                <p><?=ucfirst($response['graph_lable'])?> Wise Attendance Graph Date: <?= $response['latest_marked_attendance_date'][0]['date'] ?></p>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="classwiseattendancechart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            </di>
        </div>
    </div>
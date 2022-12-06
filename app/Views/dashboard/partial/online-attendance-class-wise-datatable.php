<div class="row">
    <div class="col-12">
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Online Attendance Report for Last Marked Attendance
                    Date: <?= $response['latest_marked_attendance_date'][0]['date'] ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="onlineattendancetable" class="table table-bordered table-striped">
                </table>
            </div>
        </div>
    </div>
</div>
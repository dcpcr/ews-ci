<div class="row ">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success text-center">
            <div class="inner">
                <h4><span class="number"><?=$response['total_number_of_students'][0]['count_total'] ?></span></h4>
                <p class="mt-4">Total students enrolled in your school
                </p>
            </div>
            <a href="<?php echo site_url('dashboard/students_list'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning text-center">
            <div class="inner">
                <h4><span class="number pb-0 mb-0"><?=$response['total_attendance']['attendance_count']?> (<?=$response['attendance_percentage']?> )</span></h4>
                <p class="mt-3 mb-0">  No. of student's attendance</br>
                    marked yesterday </br>
                </p>
            </div>
            <a href="<?php echo site_url('dashboard/bts-list'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-cyan text-center">
            <div class="inner">
                <h4><span class="number"><?=$response['total_number_of_detected_students']?></span></h4>
                <p class="mt-4 mb-0 font-weight-lighter f" style="font-size:small ">Students who are frequently absent in your school <br>
                    (Detected by EWS)
                </p>
            </div>
            <a href="<?php echo site_url('dashboard/bts-list'); ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
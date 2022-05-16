<div class='row'>
    <div class='col-lg-12'>
        <!-- SELECT2 EXAMPLE -->
        <div class='card card-default'>
            <div class='card-header'>
                <i class='fas fa-filter mr-2' style='float: left; color: #DC3545'></i>
                <h3 class='card-title' style='color: #898989'>Filters</h3>
            </div>
            <!-- /.card-header -->
            <div class='card-body'>
                <div class='row'>
                    <div class='col-md-2'>
                        <!-- /.form-group -->
                        <div class='form-group'>
                            <label>District</label>
                            <select class='select2bs4' multiple='multiple' data-placeholder='Select District'
                                    style='width: 100%;'
                                <?php if (!$filter_permissions['viewAllReports']) {
                                    echo "disabled";
                                } ?>
                            >
                                <?php
                                if ($filter_permissions['viewAllReports']) {
                                    foreach ($districts as $district) {
                                        echo '<option value = ' . $district['id'] . '>' . $district['name'] . "</option>";
                                    }
                                    echo "<option selected>All</option>";
                                } else if (!empty($user_type)) {
                                    echo '<option value = "' . $user_district_id . '"selected>' . $user_district_name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class='col-md-2'>
                        <!-- /.form-group -->
                        <div class='form-group'>
                            <label>Zone</label>
                            <select class='select2bs4' multiple='multiple' data-placeholder='Select Zone'
                                    style='width: 100%;'
                                <?php if (!$filter_permissions['viewAllReports'] && !$filter_permissions['viewReportsDistricts']) {
                                    echo "disabled";
                                } ?>
                            >
                                <?php if ($filter_permissions['viewAllReports']) {
                                    foreach ($zones as $zone) {
                                        echo '<option value = ' . $zone['id'] . '>' . $zone['name'] . "</option>";
                                    }
                                    echo "<option selected>All</option>";
                                } else if ($filter_permissions['viewReportsDistricts']) {
                                    if (!empty($user_type) && $user_type == "district") {
                                        foreach ($user_zones as $zone_id => $zone_name) {
                                            echo '<option value = ' . $zone_id . '>' . $zone_name . "</option>";
                                        }
                                    } else {
                                        //Something majorly wrong has happened!
                                    }
                                    echo "<option selected>All</option>";
                                } else {
                                    if (!empty($user_type)) {
                                        echo '<option value = "' . $user_zone_id . '"selected>' . $user_zone_name . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class='col-md-4'>
                        <div class='form-group'>
                            <label>School</label>
                            <select class='select2bs4' multiple='multiple' data-placeholder='Select School'
                                    style='width: 100%;'
                                <?php if (!$filter_permissions['viewAllReports'] && !$filter_permissions['viewReportsDistricts'] && !$filter_permissions['viewReportsZone']) {
                                    echo "disabled";
                                } ?>
                            >
                                <?php if ($filter_permissions['viewAllReports']) {
                                    foreach ($schools as $school) {
                                        echo '<option value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                    }
                                    echo "<option selected>All</option>";
                                } else if ($filter_permissions['viewReportsDistricts'] || $filter_permissions['viewReportsZone']) {
                                    if (!empty($user_type)) {
                                        foreach ($user_schools as $school) {
                                            echo '<option value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                        }
                                    } else {
                                        //Something majorly wrong has happened!
                                    }
                                    echo "<option selected>All</option>";
                                } else {
                                    if (!empty($user_type)) {
                                        echo '<option value = "' . $user_school_id . '"selected>' . $user_school_id . " - " . $user_school_name . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-1'>
                        <div class='form-group'>
                            <label>Class</label>
                            <select class='select2bs4' multiple='multiple' data-placeholder='Select Class'
                                    style='width: 100%;'>
                                <option selected>All</option>
                                <option>XII</option>
                                <option>XI</option>
                                <option>X</option>
                                <option>IX</option>
                                <option>VIII</option>
                                <option>VII</option>
                                <option>VI</option>
                                <option>V</option>
                                <option>IV</option>
                                <option>III</option>
                                <option>II</option>
                                <option>I</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-2'>
                        <div class='form-group'>
                            <label>Duration</label>
                            <div class='input-group'>
                                <button type='button' class='btn btn-default float-right' id='daterange-btn'>
                                    Duration
                                    <i class='fas fa-caret-down'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-1'>
                        <div class='form-group'>
                            <label>&nbsp;</label>
                            <div class='input-group'>
                                <button type='button' class='btn btn-primary btn-block'>View</button>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-body -->

            <!---form control row end---->
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
</div>

		

        
		
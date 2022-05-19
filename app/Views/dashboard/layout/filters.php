<div class='row'>
    <div class='col-lg-12'>
        <div class='card card-default'>
            <div class='card-header filter-header'>
                <i class='fas fa-filter mr-2' style='float: left; color: #DC3545'></i>
                <h3 class='card-title' style='color: #898989'>Filters</h3>
            </div>
            <form action="" method="POST" id="filter-form" class="filter-body">
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-md-2'>
                            <!-- /.form-group -->
                            <div class='form-group'>
                                <label>District</label>
                                <select id="districtlistbox" name="district[]" class='select2bs4' multiple='multiple'
                                        data-placeholder='Select District'
                                        style='width: 100%;'
                                    <?php if (!$filter_permissions['viewAllReports']) {
                                        echo "disabled";
                                    } ?>
                                >
                                    <?php
                                    if ($filter_permissions['viewAllReports']) {
                                        foreach ($districts as $district) {
                                            $isSelected = false;
                                            $addAll = false;
                                            if (!empty($selected_districts)) {
                                                foreach ($selected_districts as $selected_district) {
                                                    if ($selected_district == $district['id']) {
                                                        $isSelected = true;
                                                    } else {
                                                        if ($selected_district == "All") {
                                                            $addAll = true;
                                                        }
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $district['id'] . '>' . $district['name'] . "</option>";
                                            } else {
                                                echo '<option value = ' . $district['id'] . '>' . $district['name'] . "</option>";
                                            }
                                        }
                                        if (empty($selected_districts) || $addAll) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                    } else if (!empty($user_type)) {
                                        echo '<option value = "' . $user_district_id . '"selected>' . $user_district_name . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-1'>
                            <div class='form-group'>
                                <label>Zone</label>
                                <select id="zonelistbox" name="zone[]" class='select2bs4' multiple='multiple'
                                        data-placeholder='Select Zone'
                                        style='width: 100%;'
                                    <?php if (!$filter_permissions['viewAllReports'] && !$filter_permissions['viewReportsDistricts']) {
                                        echo "disabled";
                                    } ?>
                                >
                                    <?php if ($filter_permissions['viewAllReports']) {
                                        foreach ($zones as $zone) {
                                            $isSelected = false;
                                            $addAll = false;
                                            if (!empty($selected_zones)) {
                                                foreach ($selected_zones as $selected_zone) {
                                                    if ($selected_zone == $zone['id']) {
                                                        $isSelected = true;
                                                    } else {
                                                        if ($selected_zone == "All") {
                                                            $addAll = true;
                                                        }
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $zone['id'] . '>' . $zone['name'] . "</option>";
                                            } else {
                                                echo '<option value = ' . $zone['id'] . '>' . $zone['name'] . "</option>";
                                            }
                                        }
                                        if (empty($selected_zones) || $addAll ) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                    } else if ($filter_permissions['viewReportsDistricts']) {
                                        if (!empty($user_type) && $user_type == "district") {
                                            foreach ($user_zones as $zone_id => $zone_name) {
                                                $isSelected = false;
                                                $addAll = false;
                                                if (!empty($selected_zones)) {
                                                    foreach ($selected_zones as $selected_zone) {
                                                        if ($selected_zone == $zone_id) {
                                                            $isSelected = true;
                                                        } else {
                                                            if ($selected_zone == "All") {
                                                                $addAll = true;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($isSelected) {
                                                    echo '<option selected value = ' . $zone_id . '>' . $zone_name . "</option>";
                                                } else {
                                                    echo '<option value = ' . $zone_id . '>' . $zone_name . "</option>";
                                                }
                                            }
                                        } else {
                                            //Something majorly wrong has happened!
                                        }
                                        if (empty($selected_zones) || $addAll) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                    } else {
                                        if (!empty($user_type)) {
                                            echo '<option value = "' . $user_zone_id . '"selected>' . $user_zone_name . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-group'>
                                <label>School</label>
                                <select id="schoollistbox" name="school[]" class='select2bs4' multiple='multiple'
                                        data-placeholder='Select School'
                                        style='width: 100%;'
                                    <?php if (!$filter_permissions['viewAllReports'] && !$filter_permissions['viewReportsDistricts'] && !$filter_permissions['viewReportsZone']) {
                                        echo "disabled";
                                    } ?>
                                >
                                    <?php if ($filter_permissions['viewAllReports']) {
                                        foreach ($schools as $school) {
                                            $isSelected = false;
                                            $addAll = false;
                                            if (!empty($selected_schools)) {
                                                foreach ($selected_schools as $selected_school) {
                                                    if ($selected_school == $school['id']) {
                                                        $isSelected = true;
                                                    } else {
                                                        if ($selected_school == "All") {
                                                            $addAll = true;
                                                        }
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                            } else {
                                                echo '<option value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                            }
                                        }
                                        if (empty($selected_schools) || $addAll) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                    } else if ($filter_permissions['viewReportsDistricts'] || $filter_permissions['viewReportsZone']) {
                                        if (!empty($user_type)) {
                                            foreach ($user_schools as $school) {
                                                $isSelected = false;
                                                $addAll = false;
                                                if (!empty($selected_schools)) {
                                                    foreach ($selected_schools as $selected_school) {
                                                        if ($selected_school == $school['id']) {
                                                            $isSelected = true;
                                                        } else {
                                                            if ($selected_school == "All") {
                                                                $addAll = true;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($isSelected) {
                                                    echo '<option selected value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                                } else {
                                                    echo '<option value = ' . $school['id'] . '>' . $school['id'] . " - " . $school['name'] . "</option>";
                                                }
                                            }
                                            if (empty($selected_schools) || $addAll) {
                                                echo "<option selected>All</option>";
                                            } else {
                                                echo "<option>All</option>";
                                            }

                                        } else {
                                            //Something majorly wrong has happened!
                                        }
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
                                <select id="classlistbox" name="class[]" class='select2bs4' multiple='multiple'
                                        data-placeholder='Select Class'
                                        style='width: 100%;'>

                                    <?php
                                    $classes = ['XII', 'XI', 'X', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 'III', 'II', 'I'];
                                    foreach ($classes as $class) {
                                        $isSelected = false;
                                        $addAll = false;
                                        if (!empty($selected_classes)) {
                                            foreach ($selected_classes as $selected_class) {
                                                if ($selected_class == $class) {
                                                    $isSelected = true;
                                                } else {
                                                    if ($selected_class == "All") {
                                                        $addAll = true;
                                                    }
                                                }
                                            }
                                        }
                                        if ($isSelected) {
                                            echo "<option selected>$class</option>";
                                        } else {
                                            echo "<option>$class</option>";
                                        }
                                    }
                                    if (empty($selected_classes) || $addAll) {
                                        echo "<option selected>All</option>";
                                    } else {
                                        echo "<option>All</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <div class='form-group'>
                                <label>Duration</label>
                                <div class='input-group'>
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt">
                                    </i>
                                </span>
                                    <?php
                                    if (empty($selected_duration)) {
                                        echo "<input type='text' name='duration' class='form-control float-right'
                                           id='daterange-btn'>";

                                    } else {
                                        echo "<input type='text' name='duration' class='form-control float-right'
                                           id='daterange-btn' value = '$selected_duration)'>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-1'>
                            <div class='form-group'>
                                <label>&nbsp;</label>
                                <div class='input-group'>
                                    <input type='submit' class='btn btn-primary btn-block' value="View">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

		

        
		
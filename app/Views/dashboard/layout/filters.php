<div class='row'>
    <div class='col-lg-12'>
        <div class='card card-default'>
            <div class='card-header filter-header'>
                <i class='fas fa-filter mr-2' style='float: left; color: #DC3545'></i>
                <h3 class='card-title' style='color: #898989'>Filters</h3>
            </div>
            <form action="" method="GET" id="filter-form" class="filter-body">
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
                                        if ($selected_districts == ['All']) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                        foreach ($user_district as $id => $name) {
                                            $isSelected = false;
                                            if (!empty($selected_districts)) {
                                                foreach ($selected_districts as $selected_district) {
                                                    if ($selected_district == $id) {
                                                        $isSelected = true;
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $id . '>' . $name . "</option>";
                                            } else {
                                                echo '<option value = ' . $id . '>' . $name . "</option>";
                                            }
                                        }
                                    } else if (!empty($user_type)) {
                                        echo '<option value = "' . array_keys($user_district)[0] . '"selected>' . $user_district[array_keys($user_district)[0]] . "</option>";
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
                                        if ($selected_zones == ['All']) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                        foreach ($user_zone as $id => $name) {
                                            $isSelected = false;
                                            if (!empty($selected_zones)) {
                                                foreach ($selected_zones as $selected_zone) {
                                                    if ($selected_zone == $id) {
                                                        $isSelected = true;
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $id . '>' . $name . "</option>";
                                            } else {
                                                echo '<option value = ' . $id . '>' . $name . "</option>";
                                            }
                                        }
                                    } else if ($filter_permissions['viewReportsDistricts']) {
                                        if (!empty($user_type) && $user_type == "district") {
                                            if ($selected_zones == ['All']) {
                                                echo "<option selected>All</option>";
                                            } else {
                                                echo "<option>All</option>";
                                            }
                                            foreach ($user_zone as $id => $name) {
                                                $isSelected = false;
                                                if (!empty($selected_zones)) {
                                                    foreach ($selected_zones as $selected_zone) {
                                                        if ($selected_zone == $id) {
                                                            $isSelected = true;
                                                        }
                                                    }
                                                }
                                                if ($isSelected) {
                                                    echo '<option selected value = ' . $id . '>' . $name . "</option>";
                                                } else {
                                                    echo '<option value = ' . $id . '>' . $name . "</option>";
                                                }
                                            }
                                        } else {
                                            //Something majorly wrong has happened!
                                        }
                                    } else {
                                        if (!empty($user_type)) {
                                            echo '<option value = "' . array_keys($user_zone)[0] . '"selected>' . $user_zone[array_keys($user_zone)[0]] . "</option>";
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
                                        if ($selected_schools == ['All']) {
                                            echo "<option selected>All</option>";
                                        } else {
                                            echo "<option>All</option>";
                                        }
                                        foreach ($user_school as $id => $name) {
                                            $isSelected = false;
                                            if (!empty($selected_schools)) {
                                                foreach ($selected_schools as $selected_school) {
                                                    if ($selected_school == $id) {
                                                        $isSelected = true;
                                                    }
                                                }
                                            }
                                            if ($isSelected) {
                                                echo '<option selected value = ' . $id . '>' . $id . " - " . $name . "</option>";
                                            } else {
                                                echo '<option value = ' . $id . '>' . $id . " - " . $name . "</option>";
                                            }
                                        }
                                    } else if ($filter_permissions['viewReportsDistricts'] || $filter_permissions['viewReportsZone']) {
                                        if (!empty($user_type)) {
                                            if ($selected_schools == ['All']) {
                                                echo "<option selected>All</option>";
                                            } else {
                                                echo "<option>All</option>";
                                            }
                                            foreach ($user_school as $id => $name) {
                                                $isSelected = false;
                                                if (!empty($selected_schools)) {
                                                    foreach ($selected_schools as $selected_school) {
                                                        if ($selected_school == $id) {
                                                            $isSelected = true;
                                                        }
                                                    }
                                                }
                                                if ($isSelected) {
                                                    echo '<option selected value = ' . $id . '>' . $id . " - " . $name . "</option>";
                                                } else {
                                                    echo '<option value = ' . $id . '>' . $id . " - " . $name . "</option>";
                                                }
                                            }
                                        } else {
                                            //Something majorly wrong has happened!
                                        }
                                    } else {
                                        if (!empty($user_type)) {
                                            echo '<option value = "' . array_keys($user_school)[0] . '"selected>' . array_keys($user_school)[0] . " - " . $user_school[array_keys($user_school)[0]] . "</option>";
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
                                    $classes = ['XII', 'XI', 'X', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 'III', 'II', 'I', 'KG', 'Nursery'];
                                    if ($selected_classes == ['All']) {
                                        echo "<option selected>All</option>";
                                    } else {
                                        echo "<option>All</option>";
                                    }
                                    foreach ($classes as $class) {
                                        $isSelected = false;
                                        if (!empty($selected_classes)) {
                                            foreach ($selected_classes as $selected_class) {
                                                if ($selected_class == $class) {
                                                    $isSelected = true;
                                                }
                                            }
                                        }
                                        if ($isSelected) {
                                            echo "<option selected>$class</option>";
                                        } else {
                                            echo "<option>$class</option>";
                                        }
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
                                    <input type='submit' class='btn btn-primary btn-block' value="Go">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

		

        
		
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-header ml-1 mt-4 text-sm" style="color: #898989">MENU</li>

            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/case'); ?>" class="nav-link ">
                    <p>Case Status</p>
                </a>
            </li>

            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/absenteeism/'); ?>" class="nav-link ">
                    <p>Reasons of Absenteeism</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/highrisk/'); ?>" class="nav-link ">
                    <p>
                        High Risk Cases
                    </p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/homevisits/'); ?>" class="nav-link ">
                    <p>
                        Home Visits
                    </p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/call/'); ?>" class="nav-link ">
                    <p>
                        Call Disposition
                    </p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/attendance/'); ?>" class="nav-link ">
                    <p>
                        Attendance Performance
                    </p>
                </a>
            </li>
            <!--<li class="nav-header"></li>-->
        </ul>
    </nav>

</div>
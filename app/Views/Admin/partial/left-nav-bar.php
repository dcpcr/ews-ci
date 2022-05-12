<!-- Sidebar -->

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link" style="background-color: #DC3545">
                <i class="nav-icon fas fa-tachometer-alt" style="color: white"></i>
                <p style="color: white">
                    Dashboard Menu
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item mb-1 mt-1">
                    <a href="<?php echo site_url('dashboard/student'); ?>" class="nav-link ">
                        <i class="far fa-chart-bar nav-icon"></i>
                        <p>Student Report</p>
                    </a>
                </li>

                <li class="nav-item mb-1 mt-1">
                    <a href="<?php echo site_url('dashboard/absenteeism/'); ?>" class="nav-link ">
                        <i class="fas fa-child nav-icon"></i>
                        <p>Absenteeism</p>
                    </a>
                </li>
                <li class="nav-item mb-1 mt-1">
                    <a href="<?php echo site_url('dashboard/suomoto/'); ?>" class="nav-link ">
                        <i class="nav-icon far fa-sticky-note"></i>
                        <p>
                            Suo-Moto Cases
                            <span class="right badge badge-danger">DCPCR</span>
                        </p>
                    </a>

                </li>
                <li class="nav-item mb-1 mt-1">
                    <a href="<?php echo site_url('dashboard/followup/'); ?>" class="nav-link ">
                        <i class="nav-icon fas fa-phone"></i>
                        <p>
                            Calls & Follow Ups
                        </p>
                    </a>
                </li>
                <li class="nav-item mb-1 mt-1">
                    <a href="<?php echo site_url('dashboard/attendance/'); ?>" class="nav-link ">
                        <i class="nav-icon fas fa-city"></i>
                        <p>
                            Attendance Status
                        </p>
                    </a>
                </li>

            </ul>
        </li>


        <!--<li class="nav-header"></li>-->

    </ul>
</nav>
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
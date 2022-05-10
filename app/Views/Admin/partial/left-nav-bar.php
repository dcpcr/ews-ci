<!-- Sidebar -->


<!-- SidebarSearch Form -->
<div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div>

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo site_url('AdminController/studentreport/'); ?>" class="nav-link ">
                        <i class="far fa-chart-bar nav-icon"></i>
                        <p>Student Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo site_url('AdminController/absenteeism/'); ?>" class="nav-link ">
                        <i class="fas fa-child nav-icon"></i>
                        <p>Absenteeism</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('AdminController/suomotocases/'); ?>" class="nav-link ">
                        <i class="nav-icon far fa-sticky-note"></i>
                        <p>
                            Suo-Moto Cases
                            <span class="right badge badge-danger">DCPCR</span>
                        </p>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('AdminController/followup/'); ?>" class="nav-link ">
                        <i class="nav-icon fas fa-phone"></i>
                        <p>
                            Calls & Follow Ups
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('AdminController/attendancestatus/'); ?>" class="nav-link ">
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
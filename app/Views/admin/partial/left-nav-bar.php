<!-- Sidebar -->

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-header" style="text-decoration: underline">Menu</li>

        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/student'); ?>" class="nav-link ">
                <p>Detected Cases</p>
                <i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo site_url('dashboard/student#graph'); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Summary</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url('dashboard/student#list'); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Details</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/absenteeism/'); ?>" class="nav-link ">
                <p>Reasons of Absenteeism</p>
            </a>
        </li>
        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/suomoto/'); ?>" class="nav-link ">
                <p>
                    Suo-Moto (High Risk) Cases
                </p>
            </a>
        </li>
        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/followup/'); ?>" class="nav-link ">
                <p>
                    Home Visits
                </p>
            </a>
        </li>
        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/followup/'); ?>" class="nav-link ">
                <p>
                    Calls & Follow Ups
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
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
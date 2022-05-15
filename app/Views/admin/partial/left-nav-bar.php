<!-- Sidebar -->

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-header">Menu</li>


        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/student'); ?>" class="nav-link ">

                <p>Student Report</p>
            </a>
        </li>

        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/absenteeism/'); ?>" class="nav-link ">

                <p>Absenteeism</p>
            </a>
        </li>
        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/suomoto/'); ?>" class="nav-link ">

                <p>
                    Suo-Moto Cases
                    <span class="right badge badge-danger">DCPCR</span>
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
                    Attendance Status
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">

                <p>
                    Mailbox
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="pages/mailbox/mailbox.html" class="nav-link">

                        <p>Inbox</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/mailbox/compose.html" class="nav-link">

                        <p>Compose</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/mailbox/read-mail.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Read</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item mb-1 mt-1">
            <a href="<?php echo site_url('dashboard/attendance/'); ?>" class="nav-link ">

                <p>
                    logout
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
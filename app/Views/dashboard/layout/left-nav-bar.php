<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-header ml-1 mt-4 text-sm" style="color: #898989">MENU</li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/summary'); ?>" class="nav-link summary"
                   id="summary-nav">
                    <p>Summary</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/online-attendance'); ?>" class="nav-link online-attendance"
                   id="online-attendance-nav">
                    <p>Status of Online Attendance</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/absenteeism-reason'); ?>" class="nav-link absenteeism-reason"
                   id="absenteeism-reason-nav">
                    <p>Reason For Absenteeism</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/frequent-absenteeism'); ?>"
                   class="nav-link frequent-absenteeism"
                   id="frequent-absenteeism-nav">
                    <p>Frequent Absenteeism</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/case'); ?>" class="nav-link case" id="case-nav">
                    <p>Case Status</p>
                </a>
            </li>

            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/attendance'); ?>" class="nav-link attendance"
                   id="attendance-nav">
                    <p>
                        Attendance Performance
                    </p>
                </a>
            </li>
        </ul>
    </nav>

</div>
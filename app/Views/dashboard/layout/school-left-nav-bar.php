<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header ml-1 mt-4 text-sm" style="color: #898989">MENU</li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/case-status'); ?>" class="nav-link case-status"
                   id="case-status-nav">
                    <p>Summary</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/onlineattendance'); ?>" class="nav-link onlineattendance"
                   id="onlineattendance-nav">
                    <p>Status of Online Attendance</p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/absenteeism'); ?>" class="nav-link absenteeism"
                   id="absenteeism-nav">
                    <p>
                        Reason For Absenteeism
                    </p>
                </a>
            </li>
            <li class="nav-item mb-1 mt-1">
                <a href="<?php echo site_url('dashboard/frequentabsenteeism'); ?>" class="nav-link attendance"
                   id="frequentabsenteeism-nav">
                    <p>
                        Frequent Absenteeism
                    </p>
                </a>
            </li>
        </ul>
    </nav>
</div>
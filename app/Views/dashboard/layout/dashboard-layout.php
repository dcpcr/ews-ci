<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" class="csrf">
    <title></title>

    <!-- Add our own ews styesheet -->
    <link rel="stylesheet" href="/assets-adminlte/ews-css/common.css">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets-adminlte/dist/css/adminlte.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/daterangepicker/daterangepicker.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="/assets-adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets-adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/dropzone/min/dropzone.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets-adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets-adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets-adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar Start-->

    <nav class="main-header navbar navbar-expand  navbar-light">
        <?= $this->include('dashboard/layout/top-nav-bar'); ?>
    </nav>
    <aside class="main-sidebar sidebar-light-primary elevation-0">
        <div class="navbar-light">
            <?= $this->include('dashboard/layout/brand-logo-name'); ?>
        </div>
        <?php
        if (!$filter_permissions['viewAllReports']) {
            echo $this->include('dashboard/layout/school-left-nav-bar');
        } else {
            echo $this->include('dashboard/layout/left-nav-bar');
        }

        ?>
    </aside>

    <div class="content-wrapper">
        <?= $this->include('dashboard/layout/page-header-details'); ?>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <?= $this->include('dashboard/layout/filters'); ?>
                <?= $this->renderSection("graph-section") ?>
                <?= $this->renderSection("table-section-1") ?>
                <?= $this->renderSection("table-section-2") ?>
            </div>
        </div>
        <!-- Right Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.right-control-sidebar -->
    </div>
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="/assets-adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/assets-adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="/assets-adminlte/dist/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="/assets-adminlte/plugins/chart.js/Chart.min.js"></script>

    <!-- Select2 -->
    <script src="/assets-adminlte/plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="/assets-adminlte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="/assets-adminlte/plugins/moment/moment.min.js"></script>
    <script src="/assets-adminlte/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="/assets-adminlte/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="/assets-adminlte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/assets-adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="/assets-adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="/assets-adminlte/plugins/bs-stepper/js/bs-stepper.min.js"></script>

    <!-- jQuery Knob -->
    <script src="/assets-adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- Sparkline -->
    <script src="/assets-adminlte/plugins/sparklines/sparkline.js"></script>

    <script src="/assets-adminlte/ews-js/common.js"></script>

    <!-- Page specific script -->
    <?= $this->include('dashboard/partial/scripts/filter-script'); ?>
    <?= $this->renderSection("page-specific-script-section") ?>
    <!-- add Page specific script -->

    <!-- add dependent script here-->
    <?= $this->renderSection("add-js-file-section") ?>
</body>

</html>
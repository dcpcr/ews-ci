<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

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
    <link rel="stylesheet" href="/assets-adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar Start-->

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <?= $this->include('admin/partial/top-nav-bar'); ?>
    </nav>
    <!-- /.navbar end-->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->

        <?= $this->include('admin/partial/brand-logo-name'); ?>
        <?= $this->include('admin/partial/user-name-image'); ?>
        <?= $this->include('admin/partial/left-nav-bar'); ?>

        <!-- main sidebar container end-->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) and breadcrumb-item-->

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">


                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!---Form control---->
                    <?= $this->include('admin/partial/filters'); ?>

                    <!---form control end---->
                    <!---Information---->
                    <?= $this->renderSection("report-section") ?>

                    <?= $this->renderSection("table-section") ?>

                    <!---Information end---->

                    <!-- /.main content end-->
                </div>
                <!-- /.content-wrapper -->
            </div>
            <!-- Right Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.right-control-sidebar -->

            <!-- Main Footer -->
        </div>
        <?= $this->include('admin/partial/footer'); ?>

        <!-- main footer end -->

        <!-- ./wrapper -->

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
        <!-- dropzonejs -->
        <script src="/assets-adminlte/plugins/dropzone/min/dropzone.min.js"></script>

        <!-- jQuery Knob -->
        <script src="/assets-adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- Sparkline -->
        <script src="/assets-adminlte/plugins/sparklines/sparkline.js"></script>

        <!-- add dependent script here-->
        <?= $this->renderSection("add-js-file-section") ?>

        <!-- Page specific script -->
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                //Datemask dd/mm/yyyy
                $('#datemask').inputmask('dd/mm/yyyy', {
                    'placeholder': 'dd/mm/yyyy'
                })
                //Datemask2 mm/dd/yyyy
                $('#datemask2').inputmask('mm/dd/yyyy', {
                    'placeholder': 'mm/dd/yyyy'
                })
                //Money Euro
                $('[data-mask]').inputmask()

                //Date picker
                $('#reservationdate').datetimepicker({
                    format: 'L'
                });

                //Date and time picker
                $('#reservationdatetime').datetimepicker({
                    icons: {
                        time: 'far fa-clock'
                    }
                });

                //Date range picker
                $('#reservation').daterangepicker()
                //Date range picker with time picker
                $('#reservationtime').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'MM/DD/YYYY hh:mm A'
                    }
                })
                //Date range as a button
                $('#daterange-btn').daterangepicker({
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                                .subtract(1, 'month').endOf('month')
                            ]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                            'MMMM D, YYYY'))
                    }
                )

                //Timepicker
                $('#timepicker').datetimepicker({
                    format: 'LT'
                })

                //Bootstrap Duallistbox
                $('.duallistbox').bootstrapDualListbox()

                //Colorpicker
                $('.my-colorpicker1').colorpicker()
                //color picker with addon
                $('.my-colorpicker2').colorpicker()

                $('.my-colorpicker2').on('colorpickerChange', function (event) {
                    $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
                })

                $("input[data-bootstrap-switch]").each(function () {
                    $(this).bootstrapSwitch('state', $(this).prop('checked'));
                })

            })
            // BS-Stepper Init
            document.addEventListener('DOMContentLoaded', function () {
                window.stepper = new Stepper(document.querySelector('.bs-stepper'))
            })

            // DropzoneJS Demo Code Start
            Dropzone.autoDiscover = false

            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
            var previewNode = document.querySelector("#template")
            previewNode.id = ""
            var previewTemplate = previewNode.parentNode.innerHTML
            previewNode.parentNode.removeChild(previewNode)

            var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
                url: "/target-url", // Set the url
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 20,
                previewTemplate: previewTemplate,
                autoQueue: false, // Make sure the files aren't queued until manually added
                previewsContainer: "#previews", // Define the container to display the previews
                clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
            })

            myDropzone.on("addedfile", function (file) {
                // Hookup the start button
                file.previewElement.querySelector(".start").onclick = function () {
                    myDropzone.enqueueFile(file)
                }
            })

            // Update the total progress bar
            myDropzone.on("totaluploadprogress", function (progress) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
            })

            myDropzone.on("sending", function (file) {
                // Show the total progress bar when upload starts
                document.querySelector("#total-progress").style.opacity = "1"
                // And disable the start button
                file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
            })

            // Hide the total progress bar when nothing's uploading anymore
            myDropzone.on("queuecomplete", function (progress) {
                document.querySelector("#total-progress").style.opacity = "0"
            })

            // Setup the buttons for all transfers
            // The "add files" button doesn't need to be setup because the config
            // `clickable` has already been specified.
            document.querySelector("#actions .start").onclick = function () {
                myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
            }
            document.querySelector("#actions .cancel").onclick = function () {
                myDropzone.removeAllFiles(true)
            }
            // DropzoneJS Demo Code End
        </script>
        <!-- Page specific script -->
        <script>
            $(function () {
                /* jQueryKnob */

                $('.knob').knob({
                    /*change : function (value) {
                     //console.log("change : " + value);
                     },
                     release : function (value) {
                     console.log("release : " + value);
                     },
                     cancel : function () {
                     console.log("cancel : " + this.value);
                     },*/
                    draw: function () {

                        // "tron" case
                        if (this.$.data('skin') == 'tron') {

                            var a = this.angle(this.cv) // Angle
                                ,
                                sa = this.startAngle // Previous start angle
                                ,
                                sat = this.startAngle // Start angle
                                ,
                                ea // Previous end angle
                                ,
                                eat = sat + a // End angle
                                ,
                                r = true

                            this.g.lineWidth = this.lineWidth

                            this.o.cursor &&
                            (sat = eat - 0.3) &&
                            (eat = eat + 0.3)

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.value)
                                this.o.cursor &&
                                (sa = ea - 0.3) &&
                                (ea = ea + 0.3)
                                this.g.beginPath()
                                this.g.strokeStyle = this.previousColor
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea,
                                    false)
                                this.g.stroke()
                            }

                            this.g.beginPath()
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat,
                                false)
                            this.g.stroke()

                            this.g.lineWidth = 2
                            this.g.beginPath()
                            this.g.strokeStyle = this.o.fgColor
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this
                                .lineWidth * 2 / 3, 0, 2 * Math.PI, false)
                            this.g.stroke()

                            return false
                        }
                    }
                })
                /* END JQUERY KNOB */

                //INITIALIZE SPARKLINE CHARTS
                var sparkline1 = new Sparkline($('#sparkline-1')[0], {
                    width: 240,
                    height: 70,
                    lineColor: '#92c1dc',
                    endColor: '#92c1dc'
                })
                var sparkline2 = new Sparkline($('#sparkline-2')[0], {
                    width: 240,
                    height: 70,
                    lineColor: '#f56954',
                    endColor: '#f56954'
                })
                var sparkline3 = new Sparkline($('#sparkline-3')[0], {
                    width: 240,
                    height: 70,
                    lineColor: '#3af221',
                    endColor: '#3af221'
                })

                sparkline1.draw([1000, 1200, 920, 927, 931, 1027, 819, 930, 1021])
                sparkline2.draw([515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921])
                sparkline3.draw([15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21])

            })
        </script>
        <!-- add Page specific script -->
        <?= $this->renderSection("page-specific-script-section") ?>

</body>

</html>
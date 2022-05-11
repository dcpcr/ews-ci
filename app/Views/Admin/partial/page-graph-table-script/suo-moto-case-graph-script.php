

		<!-- ChartJS -->
		<script src="/assets-adminlte/plugins/chart.js/Chart.min.js"></script>
		<!-- DataTables  & Plugins -->
		<script src="/assets-adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
		<script src="/assets-adminlte/plugins/jszip/jszip.min.js"></script>
		<script src="/assets-adminlte/plugins/pdfmake/pdfmake.min.js"></script>
		<script src="/assets-adminlte/plugins/pdfmake/vfs_fonts.js"></script>
		<script src="/assets-adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
		<script src="/assets-adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>




        <!-- Page specific script -->

		<!-- Page specific script -->
<script>
  $(function () {
	 //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Juvenile Justice-700-25%',
          'Child Labour -500-30%',
          'Health & Nutrition -400-25%',
          'POCSO -600-25%',
          'Education -300-25%',
          'Missing child -100-25%',
		  'Others -280-25%',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100,280],
          backgroundColor : ['#717dc9', '#6cffce', '#203E90', '#001798', '#AD9ECBFF', '6CFFCEFF','#511798'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })
  })
</script>
<!-- datatable specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>		
	<!--          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de','#606ec2'],
-->
</body>

</html>

		<!-- ChartJS -->
		<script src="/assets-adminlte/plugins/chart.js/Chart.min.js"></script>



<script>
  $(function () {
	   //---------------------
    //- STACKED BAR CHART -
    //---------------------
	var stackchartdata = {
      labels  : ['Sickness', 'Incarceration', 'Moved to Different Place', 'Child Marraige', 'Child Labour', 'Sexual offences/ Inappropriate Bihaviour', 'Juvenile Injustice','Other'],
      datasets: [
        {
          label               : 'Male',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90,20]
        },
		 {
          label               : 'Female',
          backgroundColor     : 'rgba(160,141,188,0.9)',
          borderColor         : 'rgba(160,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90,25]
        },
        {
          label               : 'Transgender',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40,30]
        },
      ]
    }
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, stackchartdata)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
  })
   
  })
</script>
		
</body>

</html>
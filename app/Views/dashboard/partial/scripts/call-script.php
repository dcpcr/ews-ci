<?= $this->include('dashboard/partial/scripts/data-table-script'); ?>
<!-- Page specific script -->
<script>
    $(function () {
        //---------------------
        //- STACKED BAR CHART -
        //---------------------
        var stackchartdata = {
            labels: ['Successful Contact', 'Reattempt Required', 'Untracable', 'Yet to Contact', 'Refusals'],
            datasets: [
                {
                    label: 'Call Disposition',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56]
                },
            ]
        }
        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        var stackedBarChartData = $.extend(true, {}, stackchartdata)

        var stackedBarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
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
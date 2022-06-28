$(function () {
    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackchartdata = {
        labels: reasonslable,
        datasets: [
            {
                label: 'Male',
                backgroundColor: 'rgb(176,230,188)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: dataMale,
            },
            {
                label: 'Female',
                backgroundColor: 'rgb(107,194,225)',
                borderColor: 'rgba(160,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: dataFemale
            },
            {
                label: 'Transgender',
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: '#9fa8bd',
                pointHighlightFill: '#c6cbd7',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: dataTransgender
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

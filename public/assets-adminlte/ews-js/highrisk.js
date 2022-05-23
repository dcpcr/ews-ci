$(function () {
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData = {
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
                data: [700, 500, 400, 600, 300, 100, 280],
                backgroundColor: ['#717dc9', '#6cffce', '#203E90', '#001798', '#AD9ECBFF', '6CFFCEFF', '#511798'],
            }
        ]
    }
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    })
})

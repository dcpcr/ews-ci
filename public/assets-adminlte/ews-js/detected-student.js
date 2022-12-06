$(function () {
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#detcetedstudentgender').get(0).getContext('2d')
    var donutData = {
        labels: ['Male', 'Female', 'Transgender',],
        datasets: [{
            data: detectedStudentGenderData, backgroundColor: ['#AD9ECBFF', '#6CFFCEFF', '#511798'],
        }]
    }
    var donutOptions = {
        maintainAspectRatio: false, responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut', data: donutData, options: donutOptions
    })
})
$(function () {
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#detcetedstudentclass').get(0).getContext('2d')
    var donutData = {
        labels: detectedCaseClassLabel, datasets: [{
            data: detectedCaseClassWiseCount,
            backgroundColor: ['#717dc9', '#e46cff', '#203E90', '#001798', '#AD9ECBFF', '#511798', '#4150ac', '#4c5bbb', '#5f6cc2', '#717dc9', '#838ed0', '#969fd7', '#a8b0de'],
        }]
    }
    var donutOptions = {
        maintainAspectRatio: false, responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut', data: donutData, options: donutOptions
    })
})


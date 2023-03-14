$(function () {
    var lineChartCanvas = $('#areaChart').get(0).getContext('2d')
    var lineChartData = {
        labels: attendanceLabel, datasets: [{
            label: 'Online Attendance Marked',
            backgroundColor: 'rgba(255,255,255,0)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: 5,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: attendanceCountData
        }, {
            label: 'Total Students In School',
            backgroundColor: 'rgba(255,255,255,0)',
            borderColor: 'rgba(217,183,15,0.8)',
            pointRadius: 0,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: totalStudentCountData
        },]
    }
    var lineChartOptions = {
        maintainAspectRatio: false, responsive: true, legend: {
            display: true
        }, scales: {
            xAxes: [{
                gridLines: {
                    display: true,
                }, scaleLabel: {
                    display: true, labelString: 'Date'
                },

            }], yAxes: [{
                gridLines: {
                    display: true,
                }, scaleLabel: {
                    display: true, labelString: 'Number of Student'
                },
            }]
        }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(lineChartCanvas, {
        type: 'line', data: lineChartData, options: lineChartOptions
    })

})
$(function () {
    $("#onlineattendancetable").DataTable({
        "pageLength": 10,
        data: attendancedata,
        columns: [
            {data: "Serial_no", title: 'S.No'},
            {data: column, title: column},
            {
            data: 'Total_Students',
            title: 'Total Students',
            render: DataTable.render.number(',')
        },
            {data: 'Attendance_Marked', title: 'Attendance Marked', render: DataTable.render.number(',')}, {
            data: 'Attendance_Marked_Percent',
            title: 'Attendance Marked %',
            render: $.fn.dataTable.render.number('', '', '', '', "%")
        },
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#onlineattendancetable_wrapper .col-md-6:eq(0)');
});
$(function () {
    $("#onlineattendancepercentagetable").DataTable({
        "pageLength": 10,
        /*data: attendancedata,
        columns: [
            {
                data: "Serial_no",
                title: 'S.No'
            },
            {
                data: "district",
                title: 'District'
            },
            {
                data: "zone",
                title: 'Zone'
            },
            {
                data: column,
                title: column
            },
            {
                data: 'Total_Students', title: 'Total Students',
                render: DataTable.render.number(',')
            },
            {
                data: 'Attendance_Marked',
                title: 'Attendance Marked',
                render: DataTable.render.number(',')
            },
            {
                data: 'Attendance_Marked_Percent',
                title: 'Attendance Marked %',
                render: $.fn.dataTable.render.number('', '', '', '', "%")
            },
        ],*/
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#onlineattendancepercentagetable_wrapper .col-md-6:eq(0)');
});

$(document).ready(function () {
    $("#duration").empty();
});
$(function () {
    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackchartdata = {
        labels: lablevalue, datasets: [{
            label: graph_legend,
            backgroundColor: 'rgba(100, 214, 222, 1)',
            borderColor: 'rgba(210, 214, 222, 1)',
            pointRadius: false,
            pointColor: 'rgba(210, 214, 222, 1)',
            pointStrokeColor: '#c1c7d1',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data: labledata
        },]
    }
    var stackedBarChartCanvas = $('#classwiseattendancechart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, stackchartdata)

    var stackedBarChartOptions = {
        responsive: true, maintainAspectRatio: false, scales: {
            xAxes: [{
                stacked: true,
            }], yAxes: [{
                stacked: true
            }]
        }
    }

    new Chart(stackedBarChartCanvas, {
        type: 'bar', data: stackedBarChartData, options: stackedBarChartOptions
    })

})
$(function () {
    $("#attendancetable").DataTable({
        "pageLength": 40,
        data: attendancedata,
        columns: [
            {data: "Serial_no", title: 'S.No'},
            {data: 'School', title: 'School'},
            {data: 'District', title: 'District'},
            {data: 'Zone', title: 'Zone'},
            {data: 'Total_Students', title: 'Total Students', render: DataTable.render.number( ',')},
            {data: 'Attendance_Marked', title: 'Attendance Marked', render: DataTable.render.number( ',')},
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#attendancetable_wrapper .col-md-6:eq(0)');
});

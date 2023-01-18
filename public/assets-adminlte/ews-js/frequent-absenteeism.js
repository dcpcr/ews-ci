$(function () {
    $("#frequentabsenteeismtable").DataTable({
        "pageLength": 40,

        data: frequentAbsenteeismData,
        columns: [
            {data: "serial_no", title: 'S.No'},
            {data: 'student_id', title: 'Student Id'},
            {data: 'student_name', title: 'Name'},
            {data: 'mobile', title: 'Mobile'},
            {data: 'class', title: 'Class'},
            {data: 'section', title: 'Section'},
            {data: 'gender', title: 'Gender'},
            {data: 'address', title: 'Address'},
            {data: 'detected_count', title: 'Number of Time Detected'},
        ],
        columnDefs: [
            {"visible": false, "targets": [7]},
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#frequentabsenteeismtable_wrapper .col-md-6:eq(0)');
});

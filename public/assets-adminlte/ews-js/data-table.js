$(function () {
    $("#casetable").DataTable({
        "pageLength": 50,
        data: casedata,
        columns: [
            {data: 'case_id', title: 'Case_Id'},
            {data: 'day', title: 'Day'},
            {data: 'status', title: 'Status'},
            {data: 'student_id', title: 'Student Id'},
            {data: 'student_name', title: 'Student Name'},
            {data: 'school_id', title: 'School Id'},
            {data: 'school_name', title: 'School Name'},
            {data: 'class', title: 'Class'},
            {data: 'gender', title: 'DoB'},
            {data: 'father', title: 'Father  Name'},
            {data: 'mother', title: 'Mother Name'},
            {data: 'mobile', title: 'mobile'},
            {data: 'address', title: 'Address'},
            {data: 'district', title: 'District'},
            {data: 'zone', title: 'Zone'},
            {data: 'detection_criteria', title: 'Detection Criteria'}
        ],
        "columnDefs": [
            {"visible": false, "targets": 7},
            {"visible": false, "targets": 8},
            {"visible": false, "targets": 9},
            {"visible": false, "targets": 10},
            {"visible": false, "targets": 11},
            {"visible": false, "targets": 12},
            {"visible": false, "targets": 13}
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');

    $("#attendancetable").DataTable({
        "pageLength": 100,
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');
});


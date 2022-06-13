$(function () {
    $("#casetable").DataTable({
        "pageLength": 40,
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
            {"visible": false, "targets": 13},
            {"visible": false, "targets": 15}
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');

});
$(function () {
    $("#attendancetable").DataTable({
        "pageLength": 40,
        data: attendancedata,
        columns: [
            {data: "Serial_no", title: 'S.No'},
            {data: 'School', title: 'School'},
            {data: 'Total_Students', title: 'Total Students'},
            {data: 'Attendance_Marked', title: 'Attendance Marked'}
        ],
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#attendancetable_wrapper .col-md-6:eq(0)');

});



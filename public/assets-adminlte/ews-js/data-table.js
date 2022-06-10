$(function () {
    $("#casetable").DataTable({
        "pageLength": 50,
        "columnDefs": [
            { "visible": false, "targets": 7 },
            { "visible": false, "targets": 8 },
            { "visible": false, "targets": 9 },
            { "visible": false, "targets": 10 },
            { "visible": false, "targets": 11 },
            { "visible": false, "targets": 12 },
            { "visible": false, "targets": 13 }
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


$(function () {
    $("#casetable").DataTable({
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
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});


$(function () {
    $("#summaryTable")
        .DataTable({
            pageLength: 40,

            data: homePageAttendanceListData,
            columns: [

                {data: "attendance_status", title: "Attendance Status"},
                {data: "father", title: "Father"},
                {data: "mother", title: "Mother"},
                {data: "mobile", title: "Mobile"},
                {data: "address", title: "Address"},
                {data: "serial_no", title: "S.No"},
                {data: "student_id", title: "Student Id"},
                {data: "district", title: "District"},
                {data: "id", title: "Id"},
                {data: "student_name", title: "Name"},
                {data: "class", title: "Class"},
                {data: "section", title: "Section"},
                {data: "gender", title: "Gender"},
                {data: "school_id", title: "School Id"},
            ],
            columnDefs: [{visible: false, targets: [0, 1, 2, 3, 4]}],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            ordering: true,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
        })
        .buttons()
        .container()
        .appendTo("#summaryTable_wrapper .col-md-6:eq(0)");
});

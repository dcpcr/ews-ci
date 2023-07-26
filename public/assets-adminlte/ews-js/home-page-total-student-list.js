$(function () {
    $("#summaryTable")
        .DataTable({
            pageLength: 40,

            data: homePageTotalStudentListData,
            columns: [
                {data: "serial_no", title: "S.No"},
                {data: "student_name", title: "Name"},
                {data: "father", title: "Father"},
                {data: "mother", title: "Mother"},
                {data: "mobile", title: "Mobile"},
                {data: "student_id", title: "Student Id"},
                {data: "address", title: "Address"},
                {data: "district", title: "District"},
                {data: "class", title: "Class"},
                {data: "section", title: "Section"},
                {data: "gender", title: "Gender"},
                {data: "dob", title: "Date of Birth"},
                {data: "school_id", title: "School Id"},
            ],
            columnDefs: [{visible: false, targets: [ 2, 3, 4,12]}],
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

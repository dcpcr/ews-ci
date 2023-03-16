$(function () {
  $("#summaryTable")
    .DataTable({
      pageLength: 40,

      data: summaryBackToSchoolList,
      columns: [
        //{ data: "case_id", title: "Case Id" },
        //{ data: "created_at", title: "Created At" },
        //{ data: "updated_at", title: "Updated At" },
        //{ data: "dob", title: "Date of birth" },
        { data: "father", title: "Father" },
        { data: "mother", title: "Mother" },
        { data: "mobile", title: "Mobile" },
        { data: "address", title: "Address" },
        { data: "date_of_bts", title: "Date of Bts" },

        { data: "serial_no", title: "S.No" },
        { data: "student_id", title: "Student Id" },
        { data: "status", title: "Status" },
        { data: "district", title: "District" },
        { data: "id", title: "Id" },
        { data: "name", title: "Name" },
        { data: "class", title: "Class" },
        { data: "section", title: "Section" },
        { data: "gender", title: "Gender" },
        { data: "school_id", title: "School Id" },
        { data: "seven_days_criteria", title: "7 days criteria" },
        { data: "thirty_days_criteria", title: "30 days criteria" },
        { data: "priority", title: "Priority" },
        { data: "day", title: "Day" },
      ],
      columnDefs: [{ visible: false, targets: [0, 1, 2, 3, 4] }],
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

$(function () {
    /* jQueryKnob */
    $(".knob").each(
        function () {
            $(this).knob({
                min: 0,
                max: max,
                readOnly: true,
                format: function (v) {
                    return (v.toLocaleString('en-US'));
                }
            });
        }
    );

    $("span.number").each(function () {
        var v = Number($(this).text()).toLocaleString('en-US');
        $(this).text(v);
    });
    /* END JQUERY KNOB */
    $("#casetable").DataTable({
        pageLength: 40,
        data: casedata,
        deferRender: true,
        columns: [
            {data: 'case_id', title: 'Case Id'},
            {data: 'day', title: 'Day'},
            {data: 'status', title: 'Status'},
            {data: 'student_id', title: 'Student Id'},
            {data: 'student_name', title: 'Student Name'},
            {data: 'school_id', title: 'School Id'},
            {data: 'school_name', title: 'School Name'},
            {data: 'class', title: 'Class'},
            {data: 'gender', title: 'Gender'},
            {data: 'dob', title: 'DoB', "searchable": false},
            {data: 'father', title: 'Father  Name', "searchable": false},
            {data: 'mother', title: 'Mother Name', "searchable": false},
            {data: 'mobile', title: 'Mobile'},
            {data: 'address', title: 'Address'},
            {data: 'district', title: 'District'},
            {data: 'zone', title: 'Zone'},
            {data: 'seven_days_criteria', title: '7 Consecutive Days'},
            {data: 'thirty_days_criteria', title: '20/30 Days'},
            {data: 'system_bts', title: 'Present Days After Detection'},
            {
                data: 'priority',
                title: 'Priority',
                render: function ( data, type, row ) {
                    // If display or filter data is requested, format the date
                    if ( type === 'sort' ) {
                        if (data === "High")
                            return 3;
                        if (data === "Medium")
                            return 2;
                        if (data === "Low")
                            return 1;
                    }
                    return data;
                }
            },
        ],
        columnDefs: [
            {"visible": false, "targets": [7, 8, 9, 10, 11, 12, 13, 14, 15]},
        ],
        responsive: false,
        lengthChange: false,
        autoWidth: false,
        ordering: true,
        order: [
            [19, 'desc'], [0, 'asc']
        ],
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');
})
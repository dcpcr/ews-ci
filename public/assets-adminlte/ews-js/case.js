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
        processing: true,
        serverSide: true,
        ajax: {
            url: ajax_url,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: "GET"
        },
        pageLength: 10,
        columns: [
            {data: 0, title: 'Case Id'},
            {data: 5, title: 'Day'},
            {data: 6, title: 'Status'},
            {data: 7, title: 'Student Id'},
            {data: 8, title: 'Student Name'},
            {data: 17, title: 'School Id'},
            {data: 18, title: 'School Name'},
            {data: 10, title: 'Class'},
            {data: 11, title: 'Section'},
            {data: 9, title: 'Gender'},
            {data: 12, title: 'DoB', "searchable": false},
            {data: 14, title: 'Father  Name', "searchable": false},
            {data: 13, title: 'Mother Name', "searchable": false},
            {data: 15, title: 'Mobile'},
            {data: 16, title: 'Address'},
            {data: 19, title: 'District', "searchable": false},
            {data: 20, title: 'Zone', "searchable": false},
            {data: 1, title: '7 Consecutive Days', "searchable": false},
            {data: 2, title: '20/30 Days', "searchable": false},
            {data: 3, title: 'Present Days After Detection', "searchable": false},
            {data: 4, title: 'Priority'},
            {data: 21, title: 'Mobile Status', "searchable": false},
            {data: 22, title: 'Date of BTS', "searchable": false},
        ],
        columnDefs: [
            {"visible": false, "targets": [7, 8, 9, 10, 11, 12, 13, 14, 15, 16]},
        ],
        responsive: false,
        lengthChange: true,
        autoWidth: false,
        ordering: true,
        order: [
            [20, 'desc'], [0, 'asc']
        ],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-4'f><'col-sm-12 col-md-2'l>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: ['download', 'copy', 'print', 'colvis']
    }).buttons().container().appendTo('#casetable_wrapper .col-md-6:eq(0)');
})

$.fn.dataTable.ext.buttons.download =
    {
        text: "Export All (CSV)",
        url: ajax_url + '&dl=Yes',
        action: function (e, dt, node, config) {
            console.log(config);
            $.ajax({
                url: config.url,
            })
                .done(function (data) {
                    let csv = MyUtil.convertJsonStringToCSV(data);
                    $.fn.dataTable.fileSave(
                        new Blob([csv]), 'Export.csv'
                    );
                });
        }
    };


$.extend( true, $.fn.dataTable.defaults, {
    "language": {
        "processing": "<span class='fa-stack fa-lg'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                       </span>&emsp;Processing ...",
        "select": {
            "rows": {
                _: '%d rows selected',
                0: 'Click row to select',
                1: '1 row selected'
            }
        }
    }
} );
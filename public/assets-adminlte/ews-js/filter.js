const $ = jQuery
$(function () {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    $('.select2bs4').on('select2:select', function (e) {
        const last_selection = e.params.data;
        const full_selection = $(this).select2('data');
        if (last_selection.text.trim() == "All") {
            $(this).val(last_selection.id).trigger('change');
        } else {
            const ids = $.map(full_selection, function (n) {
                return (n.text.trim()) != "All" ? n.id : null;
            });
            $(this).val(ids).trigger('change');
        }
    });

    //Date range as a button
    $('#daterange-btn').daterangepicker({
            ranges: {
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                'Last 30 Days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last 6 months': [moment().subtract(6, 'month'), moment().subtract(1, 'days')],
                'Last 1 year': [moment().subtract(366, 'days'), moment().subtract(1, 'days')]
            },
            showDropdowns: true,
            autoUpdateInput: true,
            startDate: start_date,
            endDate: end_date,
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                'MMMM D, YYYY'))
        }
    );

    $('#filter-form').submit(function (e) {
        $(':disabled').each(function (e) {
            $(this).removeAttr('disabled');
        })
    });
})
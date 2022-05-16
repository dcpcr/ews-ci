<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1, 'month').endOf('month'),
                    ],
                    'Last 1 year': [moment().subtract(366, 'days'), moment().subtract(1, 'days')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            }
        )
    })

    document.addEventListener('DOMContentLoaded', function () {

    })

</script>
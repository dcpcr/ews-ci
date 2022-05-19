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
                //startDate: moment().subtract(30, 'days'),
                //endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            }
        )

        // returns distinct properties from an array of objects
        function get_distinct(array, property) {
            var arr = [];
            $.each(array, function (index, value) {
                if ($.inArray(value[property], arr) == -1) {
                    arr.push(value[property]);
                }
            });
            return arr;
        }

        $('#filter-form').submit(function(e) {
            $(':disabled').each(function(e) {
                $(this).removeAttr('disabled');
            })
        });

        var schools =<?php echo json_encode($school_mappings);?>;
        var districts = <?php echo json_encode($districts);?>;
        var zones =<?php echo json_encode($zones);?>;

    })

    document.addEventListener('DOMContentLoaded', function () {

    });

    /*
        $("#filter-form").submit (function (event) {
            alert("Call ho raha hoon");
            event.preventDefault();
        });
    */

</script>
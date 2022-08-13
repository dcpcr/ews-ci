$(function () {

    const segment = MyUtil.get_segment_from_url(2);
    const nav_id = '#' + segment + "-nav";
    $(nav_id).addClass('active');

    $('[data-toggle="tooltip"]').tooltip();

    const query_data = MyUtil.get_query_data_from_url(1);

    if (typeof query_data !== 'undefined') {
        $('a.case').attr('href', $('a.case').attr('href') + '?' + query_data);
        $('a.absenteeism').attr('href', $('a.absenteeism').attr('href') + '?' + query_data);
        $('a.highrisk').attr('href', $('a.highrisk').attr('href') + '?' + query_data);
        $('a.homevisits').attr('href', $('a.homevisits').attr('href') + '?' + query_data);
        $('a.call').attr('href', $('a.call').attr('href') + '?' + query_data);
        $('a.attendance').attr('href', $('a.attendance').attr('href') + '?' + query_data);
    }
});

MyUtil = {
    get_query_data_from_url: function (index) {
        const pathname = window.location.href;
        const segments = pathname.split('?');
        return segments[index];
    },
    get_segment_from_url: function (index) {
        const pathname = window.location.pathname;
        const segments = pathname.split('/');
        return segments[index];
    },
    convertJsonStringToCSV: function (data) {
        const json = JSON.parse(data);
        const replacer = function (key, value) {
            return value === null ? '' : value
        };
        return json.map(function (row) {
            return row.map(function (value) {
                    return JSON.stringify(value, replacer);
                }
            ).join(',');
        }).join('\r\n');
    }

}
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
        $('a.summary').attr('href', $('a.summary').attr('href') + '?' + query_data);
        $('a.frequent-absenteeism').attr('href', $('a.frequent-absenteeism').attr('href') + '?' + query_data);
        $('a.absenteeism-reason').attr('href', $('a.absenteeism-reason').attr('href') + '?' + query_data);
        $('a.online-attendance').attr('href', $('a.online-attendance').attr('href') + '?' + query_data);

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
Chart.pluginService.register({
    beforeRender: function (chart) {
        if (chart.config.options.showAllTooltips) {
            // create an array of tooltips
            // we can't use the chart tooltip because there is only one tooltip per chart
            chart.pluginTooltips = [];
            chart.config.data.datasets.forEach(function (dataset, i) {
                chart.getDatasetMeta(i).data.forEach(function (sector, j) {
                    chart.pluginTooltips.push(new Chart.Tooltip({
                        _chart: chart.chart,
                        _chartInstance: chart,
                        _data: chart.data,
                        _options: chart.options.tooltips,
                        _active: [sector]
                    }, chart));
                });
            });

            // turn off normal tooltips
            chart.options.tooltips.enabled = false;
        }
    },
    afterDraw: function (chart, easing) {
        if (chart.config.options.showAllTooltips) {
            // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
            if (!chart.allTooltipsOnce) {
                if (easing !== 1)
                    return;
                chart.allTooltipsOnce = true;
            }

            // turn on tooltips
            chart.options.tooltips.enabled = true;
            Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
                tooltip.initialize();
                tooltip.update();
                // we don't actually need this since we are not animating tooltips
                tooltip.pivot();
                tooltip.transition(easing).draw();
            });
            chart.options.tooltips.enabled = false;
        }
    }
});

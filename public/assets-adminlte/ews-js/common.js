$(function () {
    function get_segment_from_url(index) {
        const pathname = window.location.pathname;
        const segments = pathname.split('/');
        return segments[index];
    }

    const segment = get_segment_from_url(2);
    const nav_id = '#' + segment + "-nav";
    $(nav_id).addClass('active');


    $('[data-toggle="tooltip"]').tooltip();

    function get_filter_data_from_url(index) {
        const pathname = window.location.href;
        const segments = pathname.split('?');
        return segments[index];
    }
    let filter_data= get_filter_data_from_url(1);

    if(typeof filter_data !== 'undefined'){

        $('a.case').attr('href',$('a.case').attr('href')+'?'+filter_data);
        $('a.absemteeism').attr('href',$('a.absemteeism').attr('href')+'?'+filter_data);
        $('a.highrisk').attr('href',$('a.highrisk').attr('href')+'?'+filter_data);
        $('a.homevisits').attr('href',$('a.homevisits').attr('href')+'?'+filter_data);
        $('a.call').attr('href',$('a.call').attr('href')+'?'+filter_data);
        $('a.attendance').attr('href',$('a.attendance').attr('href')+'?'+filter_data);
    }
});
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
});
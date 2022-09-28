<?php
helper('general');
function get_nsbbpo_url()
{
    return getenv('nsb-ticket-details-url');
}

function get_nsbbpo_token_key()
{
    return getenv('nsb-api-token');
}

function download_ticket_details($ticket_number)
{
    $url = get_nsbbpo_url() . $ticket_number;
    $api_token = get_nsbbpo_token_key();
    sleep(5);
    return get_curl_response($url, "", "", "GET", "", "", "", "", "$api_token");

}
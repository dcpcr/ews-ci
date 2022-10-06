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
    $response = get_curl_response($url, "", "", "GET", "", "", "", "", "$api_token");
    $json_response = json_decode($response);
    if (!empty($json_response[0]->ResultCode) && !empty($json_response[0]->ResultString)) {
        $result_code = $json_response[0]->ResultCode;
        $result_string = $json_response[0]->ResultString;
        log_message('error', "TechInfo Ticket Details API Failed Result Code: $result_code ResultString: $result_string");
        return [];
    } else {
        return $json_response[0];
    }
}
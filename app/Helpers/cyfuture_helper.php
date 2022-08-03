<?php
function get_cyfuture_ewsrecord_url()
{
    return getenv('cyfuture_ewsrecord_url');
}

function get_cyfuture_token_url()
{
    return getenv('cyfuture_token_url');
}

function get_cyfuture_username()
{
    return getenv('cyfuture_username');
}

function get_cyfuture_password()
{
    return getenv('cyfuture_password');
}

function get_cyfuture_method()
{
    return getenv('cyfuture_method');
}

function get_cyfuture_token()
{
    helper('general');
    $url = get_cyfuture_token_url();
    $username = get_cyfuture_username();
    $password = get_cyfuture_password();
    $method = get_cyfuture_method();
    $response = get_curl_response($url, $username, $password, $method);
    if ($response) {
        log_message("info", "API Token API call success, url - " . $url);
        $decoded_json = json_decode($response, true);
        return $decoded_json['token'];
    } else {
        log_message("error", "The API call failed, url - " . $url);

    }
}

function download_operator_form_data()
{

    $token = get_cyfuture_Token();
    $url = get_cyfuture_ewsrecord_url();
    $username = get_cyfuture_username();
    $password = get_cyfuture_password();
    $method = get_cyfuture_method();
    $from_date = '2022-07-01';
    $to_date = date("Y-m-d");
    $response = get_curl_response($url, $username, $password, $method, $from_date, $to_date, $token);
    if ($response) {
        log_message("info", "The Cyfuture EWS record API call success, url - " . $url);
        $decoded_json = json_decode($response, true);
        return $decoded_json['data'];
    } else {
        log_message("error", "The Cyfuture EWS record API call failed, url - " . $url);

    }

}
function extract_reason_data_from_cases($cases): array
{
    $keys = ['case_id', 'reason_of_absense','other_reason_of_absense'];
    return extract_values_from_objects($cases, $keys);
}

function extract_call_disposition_data_from_cases($cases): array
{
    $keys = ['case_id', 'call_dis'];
    return extract_values_from_objects($cases, $keys);
}
//raised_ticket
function extract_high_risk_data_from_cases($cases): array
{
    $keys = ['case_id', 'raised_ticket'];
    return extract_values_from_objects($cases, $keys);
}

function extract_back_to_school_data_from_cases($cases): array
{
    $keys = ['case_id', 'will_student_be_able_to_join_school'];
    return extract_values_from_objects($cases, $keys);
}

function extract_home_visit_data_from_cases($cases): array
{
    $keys = ['case_id', 'is_home_visit_required'];
    return extract_values_from_objects($cases, $keys);
}

function extract_dcpcr_helpline_ticket_data_from_cases($cases): array
{
    $keys = ['case_id', 'name_division','sub_name_division','nature_case',];
    return extract_values_from_objects($cases, $keys);
}




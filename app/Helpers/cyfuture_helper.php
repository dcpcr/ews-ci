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

function downloadOperatorFormData()
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
function extractReasonDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'reason_of_absense','other_reason_of_absense'];
    return extractValuesFromObjects($cases, $reason_keys);
}

function extractCallDispositionDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'call_dis'];
    return extractValuesFromObjects($cases, $reason_keys);
}
//raised_ticket
function extractHighRiskDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'raised_ticket'];
    return extractValuesFromObjects($cases, $reason_keys);
}

function extractBackToSchoolDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'will_student_be_able_to_join_school'];
    return extractValuesFromObjects($cases, $reason_keys);
}

function extractHomeVisitDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'is_home_visit_required'];
    return extractValuesFromObjects($cases, $reason_keys);
}

function extractDcpcrHelplineTicketDataFromCases($cases): array
{
    $reason_keys = ['case_id', 'name_division','sub_name_division','nature_case',];
    return extractValuesFromObjects($cases, $reason_keys);
}




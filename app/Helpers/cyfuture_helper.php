<?php

use App\Models\CaseDetailsModel;

function get_cyfuture_ewsrecord_url()
{
    return getenv('cyfuture_ews_record_url');
}

function get_cyfuture_helpline_ticket_url()
{
    return getenv('cyfuture_helpline_ticket_url');
}

function get_cyfuture_home_visit_url()
{
    return getenv('cyfuture_home_visit_url');
}

function get_cyfuture_yettobetakenup_url()
{
    return getenv('cyfuture_yettobetakenup_url');
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
    return [];
}

/**
 * @throws ReflectionException
 */
function insert_update_case_details($cases, $keys, $key_mappings, CaseDetailsModel $model, bool $only_insert)
{
    helper('cyfuture');
    if ($cases) {
        $reason_data = extract_values_from_objects($cases, $keys);
        $table_data = prepare_data_for_table($reason_data, $key_mappings);
        $count = $model->ignore()->insertBatch($table_data, null, 2000);
        $table_name = $model->getTableName();
        log_message("info", "$count new records inserted in $table_name table.");
        if (!$only_insert) {
            $count = $model->updateBatch($table_data, 'case_id', 2000);
            log_message("info", "$count records updated in $table_name table.");
        }
    }
}

function download_and_process_cyfuture_api_data($url, $from_date, $to_date, $func): int
{
    $token = get_cyfuture_Token();
    $record_count = 0;
    if (!empty($token)) {
        $method = get_cyfuture_method();
        $page_number = 1;
        do {
            $response = get_curl_response($url, "", "", "$method", $from_date, $to_date, $page_number, $token);
            $decoded_json = json_decode($response, true);
            $total_pages = $decoded_json['total_pages'];
            $record_count = $decoded_json['total_records'];
            if ($response && $record_count > 0) {
                $func($decoded_json['data'], $page_number);
            }
            $page_number++;
        } while ($page_number <= $total_pages);
    } else {
        log_message("error", "Cyfuture token API failed for URL:" . $url);
    }
    return $record_count;
}




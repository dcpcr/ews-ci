<?php

function get_database_name_from_db_group($db_group): string
{
    $database = new \Config\Database();
    return $database->{$db_group}['database'];
}

function get_array_from_dom_table($table, $has_header): array
{
    $rows = $table->getElementsByTagName("tr");
    $row_count = 0;
    $response_array = array();
    $header = array();
    foreach ($rows as $row) {
        $cell_count = 0;
        $cells = $row->getElementsByTagName('td');
        foreach ($cells as $cell) {
            if ($has_header && ($row_count == 0)) { //this is table header
                $header[$cell_count] = $cell->nodeValue;
            } else {
                $response_array[$row_count][$header[$cell_count]] = $cell->nodeValue;
            }
            $cell_count++;
        }
        $row_count++;
    }
    return $response_array;
}

function get_curl_response($url, $username = '', $password = '', $method = 'GET', $from_date = '', $to_date = '', $token = '')
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_MAXREDIRS => 999,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "$method",
        CURLOPT_POSTFIELDS => array('from_date' => "$from_date", 'to_date' => "$to_date"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token",
            "username: $username",
            "password: $password",
            "cache-control: no-cache"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        log_message('error', "cURL Error #:" . $err);
        return null;
    } else {
        return $response;
    }
}

function getCyfutureToken()
{
    $url = getenv('cyfuture_token_url');
    $username = getenv('cyfuture_username');
    $password = getenv('cyfuture_password');
    $method = getenv('method');
    $response = get_curl_response($url, $username, $password, $method);
    $data = json_decode($response, true);
    //TODO: ERROR handling
    return $data['token'];
}

function dump_array_in_file($array, $file_name, $exists)
{
    if ($exists) {
        $fp = fopen($file_name, 'a');
    } else {
        $fp = fopen($file_name, 'w');
    }
    // Loop through file pointer and a line
    foreach ($array as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
}

function import_data_into_db($file_name, $db_group, $table_name)
{
    $sql = "LOAD DATA LOCAL INFILE '$file_name' REPLACE INTO TABLE $table_name"
        . " FIELDS TERMINATED BY ','"
        . " OPTIONALLY ENCLOSED BY '\"' "
        . " LINES TERMINATED BY '\n'";
    $db = db_connect($db_group);
    if (!($db->simpleQuery($sql))) {
        log_message('error', "Query execute failed: error - " . $db->error()['message']);
    }
}
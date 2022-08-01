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

function get_array_from_csv($filename): array
{
    $arr = array();
    $file = fopen($filename, 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
        $arr[] = $line;
    }
    fclose($file);
    return $arr;
}

function extract_values_from_objects($objects, $keys): array
{
    $row = [];
    $result_data = [];
    foreach ($objects as $object) {
        foreach ($keys as $key) {
            $row["$key"] = $object["$key"];
        }
        $result_data[] = $row;
    }
    return $result_data;
}

function replace_key(&$array, $replaces)
{
    foreach ($array as $key => $row) {
        $new_k = new_key($key, $replaces);
        if (is_array($row)) {
            replace_key($row, $replaces);
        }

        $array[$new_k] = $row;
        if ($new_k != $key) {
            unset($array[$key]);
        }
    }
}
function new_key($column_name, $replaces)
{
    if (array_key_exists($column_name, $replaces)) {
        $column_name = str_replace($column_name, $replaces[$column_name], $column_name);
    }
    return $column_name;
}

function prepare_data_for_table($data_table, $key_mapping)
{
    /*    eg. New Mapping Key array sample ['oldkey1'=>'newkey1','oldkey2'=>'newkey2','oldkey3'=>'newkey3',]*/
    replace_key($data_table, $key_mapping);
    return $data_table;
}

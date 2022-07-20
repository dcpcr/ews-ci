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

//function to send unicode sms by making http connection
function post_to_url_unicode($url, $data)
{
    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= $key . '=' . urlencode($value) . '&';
    }
    rtrim($fields, '&');

    $post = curl_init();
    //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
    curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
    curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
    curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-length:"
        . strlen($fields)));
    curl_setopt($post, CURLOPT_HTTPHEADER, array("User-Agent:Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)"));
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($post); //result from mobile seva server
    curl_close($post);
    return $result;
}

//function to convert unicode text in UTF-8 format
function string_to_finalmessage($message)
{
    $finalmessage = "";
    $sss = "";
    for ($i = 0; $i < mb_strlen($message, "UTF-8"); $i++) {
        $sss = mb_substr($message, $i, 1, "utf-8");
        $a = 0;
        $abc = "&#" . ordutf8($sss, $a) . ";";
        $finalmessage .= $abc;
    }
    return $finalmessage;
}

//function to convet utf8 to html entity
function ordutf8($string, &$offset)
{
    $code = ord(substr($string, $offset, 1));
    if ($code >= 128) {
        if ($code < 224) $bytesnumber = 2;
        else if ($code < 240) $bytesnumber = 3;
        else if ($code < 248) $bytesnumber = 4;
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) -
            ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset++;
            $code2 = ord(substr($string, $offset, 1)) - 128;
            $codetemp = $codetemp * 64 + $code2;
        }
        $code = $codetemp;

    }
    return $code;
}

//function to send bulk unicode sms
function sendBulkUnicode($username,$password,$senderId,$messageUnicode,$mobileNos,$SecureKey,$templateId){
    $finalmessage=string_to_finalmessage(trim($messageUnicode));
    $key=hash('sha512',trim($username).trim($senderId).trim($finalmessage).trim($SecureKey));

    $data = array(
        "username" => trim($username),
        "password" => trim($password),
        "senderid" => trim($senderId),
        "content" => trim($finalmessage),
        "smsservicetype" =>"unicodemsg",
        "bulkmobno" =>trim($mobileNos),
        "key" => trim($key),
        "templateid" => trim($templateId)
    );
    $result=post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT",$data); //calling post_to_url_unicode to send bulk unicode sms
    return $result;
}
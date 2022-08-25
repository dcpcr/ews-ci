<?php

use App\Models\CdacSmsModel;

function get_cdac_username()
{
    return getenv('cdac_username');

}

function get_cdac_password()
{
    return getenv('cdac_password');
}

function get_cdac_securekey()
{
    return getenv('cdac_securekey');
}

function get_cdac_senderid()
{
    return getenv('cdac_senderid');
}


function convert_mobile_array_to_comma_separated_string($mobile_number_array)
{
    if (count($mobile_number_array) > 0) {
        $string = '';
        foreach ($mobile_number_array as $mobile) {
            $string .= $mobile['mobile'] . ",";
        }
        return $final_mobile_number_string = substr($string, 0, strlen($string) - 1);
    } else {
        return "";
    }

}

//function to send unicode sms by making http connection
function post_to_url_unicode($url, $data)
{
    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= $key . '=' . urlencode($value) . '&';
    }
    $fields = rtrim($fields, '&');

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
    $err = curl_error($post);
    curl_close($post);
    if ($err) {
        log_message('error', "cURL Error in post_to_url_unicode#:" . $err);
        return null;
    } else {
        return $result;
    }
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
    if ($code >= 128) { //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;//110xxxxx
        else if ($code < 240) $bytesnumber = 3; //1110xxxx
        else if ($code < 248) $bytesnumber = 4; //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) -
            ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset++;
            $code2 = ord(substr($string, $offset, 1)) - 128;//10xxxxxx
            $codetemp = $codetemp * 64 + $code2;
        }
        $code = $codetemp;

    }
    return $code;
}


//function to send single unicode sms
function submit_unicode_sms($message_unicode, $mobile_number, $template_id, $bulk)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $secure_key = get_cdac_securekey();
    $final_message = string_to_finalmessage(trim($message_unicode));
    $key = hash('sha512', trim($username) . trim($sender_id) . trim($final_message) . trim($secure_key));
    if (!$bulk) {
        $bulk_or_single = 'mobileno';
    } else {
        $bulk_or_single = 'bulkmobno';
    }
    $data = array(
        "username" => trim($username),
        "password" => trim($password),
        "senderid" => trim($sender_id),
        "content" => trim($final_message),
        "smsservicetype" => "unicodemsg",
        "$bulk_or_single" => trim($mobile_number),
        "key" => trim($key),
        "templateid" => trim($template_id)
    );

    return post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url_unicode to send single unicode sms
}

function fetch_sms_delivery_report($message_id)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $url = "https://msdgweb.mgov.gov.in/ReportAPI/csvreport?userid=" . $username . "&password=" . $password . "&msgid=" . $message_id . "&pwd_encrypted=false";
    helper('general');
    $response = get_curl_response($url);
    if ($response != 'You don\'t have Access to this resource. Please contact MobileSeva team.' . "\n") {
        log_message("info", "API call success, url - " . $url . " CDAC Server Message - ");
        return $response;
    } else {
        log_message("error", "The API call failed, url - " . " CDAC Server response - " . $response . $url);
        return [];
    }

}

/**
 * @throws ReflectionException
 */
//TODO: Error handling
function insert_response($response, string $template_id): void
{
    $response = str_replace("\n", "", $response);
    $response_arr = explode(',', $response);
    $statusCode = $response_arr[0];
    $messageId = $response_arr[1];
    $sms_batch_model = new CdacSmsModel();
    $result = $sms_batch_model->insertSmsBatchData($messageId, $statusCode, $template_id);
    log_message('info', "The Max id after inserting a new sms in the CdacSms Table is - " . $result[0]['id']);
}

function check_if_error($response)
{
    switch ($response) {
        case "442 : Invalid username parameter" . "\n":
        case "443 : Invalid password parameter" . "\n":
        case "Error 416 : Hash is not matching, please check your secure key" . "\n":
        case "441 : Invalid senderId parameter" . "\n":
            log_message('error', "CDAC server response: $response");
            return null;

        default:
            return $response;

    }
}

/**
 * @throws ReflectionException
 */
function send_single_unicode_sms($message_unicode, $mobile_number, $template_id)
{
    $response = submit_unicode_sms($message_unicode, $mobile_number, $template_id, false);
    if (check_if_error($response) !== null) {
        insert_response($response, $template_id);
    }
    return $response;
}

/**
 * @throws ReflectionException
 */
function send_bulk_unicode_sms($message_unicode, $mobile_numbers, $template_id)
{
    $final_mobile_number_string = convert_mobile_array_to_comma_separated_string($mobile_numbers);
    $response = submit_unicode_sms($message_unicode, $final_mobile_number_string, $template_id, true);
    if (check_if_error($response) !== null) {
        insert_response($response, $template_id);
    }
    return $response;
}



<?php

use App\Models\CdacSmsStatusModel;
use App\Models\CdacSmsModel;
use App\Models\StudentModel;

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
function submit_single_unicode_sms($message_unicode, $mobile_no, $template_id)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $secure_key = get_cdac_securekey();
    $final_message = string_to_finalmessage(trim($message_unicode));
    $key = hash('sha512', trim($username) . trim($sender_id) . trim($final_message) . trim($secure_key));

    $data = array(
        "username" => trim($username),
        "password" => trim($password),
        "senderid" => trim($sender_id),
        "content" => trim($final_message),
        "smsservicetype" => "unicodemsg",
        "mobileno" => trim($mobile_no),
        "key" => trim($key),
        "templateid" => trim($template_id)
    );

    return post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url_unicode to send single unicode sms
}

//function to send bulk unicode sms
function send_bulk_unicode($message_unicode, $mobile_numbers, $template_id)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $secure_key = get_cdac_securekey();
    $final_message = string_to_finalmessage(trim($message_unicode));
    $key = hash('sha512', trim($username) . trim($sender_id) . trim($final_message) . trim($secure_key));

    $data = array(
        "username" => trim($username),
        "password" => trim($password),
        "senderid" => trim($sender_id),
        "content" => trim($final_message),
        "smsservicetype" => "unicodemsg",
        "bulkmobno" => trim($mobile_numbers),
        "key" => trim($key),
        "templateid" => trim($template_id)
    );

    return post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url_unicode to send bulk unicode sms
}

/**
 * @throws ReflectionException
 */
function fetch_sms_delivery_report($message_id, $batch_id)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sms_batch = new CdacSmsStatusModel();
    $url = "https://msdgweb.mgov.gov.in/ReportAPI/csvreport?userid=" . $username . "&password=" . $password . "&msgid=" . $message_id . "&pwd_encrypted=false";
    helper('general');
    $response = get_curl_response($url);
    //$response = '917701891704,DELIVERED,2022-07-29 04:58:53 918758191659,DELIVERED,2022-07-29 04:58:52 919873177238,FAILEDBYTELCO,2022-07-29 04:58:49 917428194597,DELIVERED,2022-07-29 04:58:51 919818775784,DELIVERED,2022-07-29 04:59:09 917766863434,DELIVERED,2022-07-29 04:58:51 919667345583,DELIVERED,2022-07-29 04:58:51 919065414962,DELIVERED,2022-07-29 04:58:52 919818442421,DELIVERED,2022-07-29 04:58:52 919911814770,SUBMITTED,0000-00-00 00:00:00';
    $response = preg_replace('/\s/', ',', $response);
    $response_arr = explode(',', $response);
    $j = 0;
    $k = 1;
    $report_data = [];
    for ($i = 0; $i < count($response_arr) / 4; $i++) {
        $report_data[] = array(
            'sms_batch_id' => $batch_id,
            'mobile_number' => $response_arr["$j"],
            'status' => $response_arr["$k"]
        );
        $j = $j + 4;
        $k = $k + 4;
    }

    $sms_batch->insertMobileNumbersSmsBatch($report_data);

}

/**
 * @throws ReflectionException
 */
function send_sms_to_all_new_students($limit = '10000')
{
    helper('helpline_sms_template');
    $offset = 0;
    $count = 0;
    $student_model = new StudentModel();
    $total_student_count = $student_model->getTotalStudentCount();
    while ($count < $total_student_count) {
        if ($offset == 0) {
            $student_mobile = $student_model->getMobileOfNewStudents("$limit", "$offset");
            $offset++;
            $offset = $offset + $limit;
            bulk_helpline_promotion_sms($student_mobile);
        }
        $student_mobile = $student_model->getMobileOfNewStudents("$limit", "$offset");
        bulk_helpline_promotion_sms($student_mobile);
        $offset = $offset + $limit;
        $count = $count + $limit;
    }
}

/**
 * @throws ReflectionException
 */
function insert_response($response, string $template_id): void
{
    $response = str_replace("\n", "", $response);
    $response_arr = explode(',', $response);
    //TODO: Error handling
    $statusCode = $response_arr[0];
    $messageId = $response_arr[1];
    $sms_batch_model = new CdacSmsModel();
    $sms_batch_model->insertSmsBatchData($messageId, $statusCode, $template_id);

}

/**
 * @throws ReflectionException
 */
function send_single_unicode_sms($message_unicode, $mobile_number, $template_id)
{
    $response = submit_single_unicode_sms($message_unicode, $mobile_number, $template_id);
    insert_response($response, $template_id);
    return $response;

}

/**
 * @throws ReflectionException
 */
function send_bulk_unicode_sms($message_unicode, $mobile_numbers, $template_id)
{

    $final_mobile_number_string = convert_mobile_array_to_comma_separated_string($mobile_numbers);
    $response = submit_single_unicode_sms($message_unicode, $final_mobile_number_string, $template_id);
    insert_response($response, $template_id);
    return $response;

}

/**
 * @throws ReflectionException
 */
function update_sms_status_of_students_mobile_numbers()
{
    $student_model = new StudentModel();
    $mobile_numbers = $student_model->getMobileOfStudentsToUpdateDeliveryReport();
    if (count($mobile_numbers) > 0) {
        foreach ($mobile_numbers as $row) {
            $mobile_number = $row['mobile'];
            $cdac_sms_status = new CdacSmsStatusModel();
            $sms_delivery_status = $cdac_sms_status->fetchLatestSmsDeliveryReportOfMobileNumbers("$mobile_number");
            $student_model->updateSmsStatus("$mobile_number", "$sms_delivery_status");

        }
    }
}

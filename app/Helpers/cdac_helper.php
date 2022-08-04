<?php

use App\Models\SmsBatchModel;
use App\Models\SmsDeliveryReportModel;

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

function helpline_new_ticketsms($ticket_number)
{

    $messageUnicode = "आपकी शिकायत सफलतापूर्वक रजिस्टर कर ली गयी है टिकट नंबर: $ticket_number काल करने के लिए धन्यवाद। DCPCR Helpline- 9311551393";
    return $messageUnicode;
}

function helpline_new_ticket_template_id()
{
    $templateid = "1307164258584084755";
    return $templateid;
}

function helpline_closed_ticketsms($ticket_number)
{

    $messageUnicode = "आपकी शिकायत सफलतापूर्वक रजिस्टर कर ली गयी है टिकट नंबर: $ticket_number काल करने के लिए धन्यवाद। DCPCR Helpline- 9311551393";
    return $messageUnicode;
}

function helpline_closed_ticket_template_id()
{
    $templateid = "1307164258598813638";
    return $templateid;
}

function helpline_promotion_message()
{

    $messageUnicode = "बच्चे को सेहत, पोषण, या कोई और समस्या हो या संबंधित जानकारी चाहिए, तो DCPCR दिल्ली सरकार हेल्पलाइन 9311551393 पर कॉल करें।DCPCR";
    return $messageUnicode;
}

function helpline_promotion_template_id()
{
    $templateid = "1307162126864296262";
    return $templateid;
}

function convert_mobile_array_to_comma_separated_string($mobile_number_array)
{
    if (count($mobile_number_array) > 0) {
        $string = '';
        foreach ($mobile_number_array as $mobile) {
            $string .= $mobile['mobile'] . ",";
        }
        return $final_mobile_number_string = substr($string, 0, strlen($string) - 1);
    }
    else
    {
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
function send_single_unicode($username, $encryp_password, $senderid, $messageUnicode, $mobileno, $deptSecureKey, $templateid)
{
    $finalmessage = string_to_finalmessage(trim($messageUnicode));
    $key = hash('sha512', trim($username) . trim($senderid) . trim($finalmessage) . trim($deptSecureKey));

    $data = array(
        "username" => trim($username),
        "password" => trim($encryp_password),
        "senderid" => trim($senderid),
        "content" => trim($finalmessage),
        "smsservicetype" => "unicodemsg",
        "mobileno" => trim($mobileno),
        "key" => trim($key),
        "templateid" => trim($templateid)
    );

    return post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url_unicode to send single unicode sms
}

//function to send bulk unicode sms
function send_bulk_unicode($username, $encryp_password, $senderid, $messageUnicode, $mobileNos, $deptSecureKey, $templateid)
{
    $finalmessage = string_to_finalmessage(trim($messageUnicode));
    $key = hash('sha512', trim($username) . trim($senderid) . trim($finalmessage) . trim($deptSecureKey));

    $data = array(
        "username" => trim($username),
        "password" => trim($encryp_password),
        "senderid" => trim($senderid),
        "content" => trim($finalmessage),
        "smsservicetype" => "unicodemsg",
        "bulkmobno" => trim($mobileNos),
        "key" => trim($key),
        "templateid" => trim($templateid)
    );

    return post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url_unicode to send bulk unicode sms
}


/**
 * @throws ReflectionException
 */
function send_bulk_unicode_promotional_sms($mobile_numbers)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $message_unicode = helpline_promotion_message();
    $secure_key = get_cdac_securekey();
    $template_id = helpline_promotion_template_id();
    $response = send_bulk_unicode($username, $password, $sender_id, $message_unicode, $mobile_numbers, $secure_key, $template_id);
    insert_response($response, $template_id);


}

/**
 * @throws ReflectionException
 */
function send_single_unicode_promotional_sms($mobile_number)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $message_unicode = helpline_promotion_message();
    $secure_key = get_cdac_securekey();
    $template_id = helpline_promotion_template_id();
    $response = send_single_unicode($username, $password, $sender_id, $message_unicode, $mobile_number, $secure_key, $template_id);
    insert_response($response, $template_id, $mobile_number);
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
    $sms_batch_model = new SmsBatchModel();
    $sms_batch_model->insterSmsBatchData($messageId, $statusCode, $template_id);

}

function fetch_sms_delivery_report($message_ids)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sms_batch = new SmsDeliveryReportModel();
    if (count($message_ids) > 0) {
        foreach ($message_ids as $ids) {
            $url = "https://msdgweb.mgov.gov.in/ReportAPI/csvreport?userid=" . $username . "&password=" . $password . "&msgid=" . $ids['message_id'] . "&pwd_encrypted=false";
            helper('general');
            $response = get_curl_response($url);
           // $response = '917701891704,DELIVERED,2022-07-29 04:58:53 918758191659,DELIVERED,2022-07-29 04:58:52 919873177238,FAILEDBYTELCO,2022-07-29 04:58:49 917428194597,DELIVERED,2022-07-29 04:58:51 919818775784,DELIVERED,2022-07-29 04:59:09 917766863434,DELIVERED,2022-07-29 04:58:51 919667345583,DELIVERED,2022-07-29 04:58:51 919065414962,DELIVERED,2022-07-29 04:58:52 919818442421,DELIVERED,2022-07-29 04:58:52 919911814770,SUBMITTED,0000-00-00 00:00:00';
            $response = preg_replace('/\s/', ',', $response);
            $response_arr = explode(',', $response);
            $j = 0;
            $k = 1;
            for ($i = 0; $i < count($response_arr) / 4; $i++) {
                $report_data[] = array(
                    //TODO add batch id
                    'mobile_number' => $response_arr["$j"],
                    'status' => $response_arr["$k"]
                );
                $j = $j + 4;
                $k = $k + 4;
            }
            $sms_batch->insertMobileNumbersSmsBatch($report_data);

        }
    }

}


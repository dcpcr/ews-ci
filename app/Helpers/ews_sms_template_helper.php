<?php
helper('cdac');

/**
 * @throws ReflectionException
 */
function new_ews_detected_case_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . "(Student ID:" . $student_id . ")दिल्ली सरकार आपके बच्चे की स्कूल में अनुपस्थिति को लेकर चिंतित हैं। हम इस विषय पर आपसे संपर्क करना चाहते हैं। अभी बात करने के लिए कॉल - 01206985700 DCPCR";
    $template_id = "1307165967750122465";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id);
}

/**
 * @throws ReflectionException
 */
function connected_call_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . "(Student ID:" . $student_id . ") आपसे बात करके अच्छा लगा।बच्चे को रोजाना स्कूल भेजें। बच्चे से सम्बंधित कोई भी समस्या हो तो मदद के लिए कॉल करें- 01206985700 DCPCR";
    $template_id = "1307165967755457062";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}

/**
 * @throws ReflectionException
 */
function connected_call_with_ticket_raised_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . " (Student ID:" . $student_id . "): आपसे बात करके अच्छा लगा,अपने बच्चे को रोजाना स्कूल भेजें।आपकी समस्या दर्ज़ हो गयी है। बच्चों से जुड़ी कोई समस्या हो तो मदद के लिए कॉल करें- 9311551393 DCPCR";
    $template_id = "1307165967761307725";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}

/**
 * @throws ReflectionException
 */
function not_able_to_contact_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . " (Student ID: $student_id): दिल्ली सरकार आपके बच्चे की स्कूल में अनुपस्थिति को लेकर चिंतित है। हम इस विषय पर आपसे संपर्क करना चाहते हैं।अभी बात करने के लिए कॉल - 0120- 6985700 DCPCR";
    $template_id = "1307165967776258029";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}

/**
 * @throws ReflectionException
 */
function incomplete_information_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . " (Student ID:" . $student_id . "): आपसे बात करके अच्छा लगा। कॉल के दौरान बच्चे की समस्या की पूरी जानकारी नहीं मिल पायी, हम जल्द संपर्क करेंगे।अभी बात करने के लिए कॉल करें- 0120- 6985700 DCPCR";
    $template_id = "1307165967767566202";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}

/**
 * @throws ReflectionException
 */
function case_closed_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . " (Student ID:" . $student_id . "): आशा करते हैं की आपके बच्चे की समस्या हल हो गयी है। बच्चे को रोज़ाना स्कूल भेजें। और कोई समस्या हो तो मदद के लिए कॉल करें - 9311551393 DCPCR";
    $template_id = "1307165967783295949";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}

/**
 * @throws ReflectionException
 */
function bulk_helpline_promotion_sms($mobile_numbers)
{
    //$mobile_numbers is an array
    $message_unicode = "बच्चे को सेहत, पोषण, या कोई और समस्या हो या संबंधित जानकारी चाहिए, तो DCPCR दिल्ली सरकार हेल्पलाइन 9311551393 पर कॉल करें।DCPCR";
    $template_id = "1307162126864296262";
    return send_bulk_unicode_sms($message_unicode, $mobile_numbers, $template_id);
}

/**
 * @throws ReflectionException
 */
function ews_daily_report_sms($data)
{
    if ($data !== null) {
        $message_unicode = "DCPCR Early Warning System Daily Status Report: \n 1- Total New Case Detected: " . $data['Total_Case_Count'][0]['id'];
        for ($i = 0; $i < count($data['Priority_Wise_Count']); $i++) {
            $message_unicode .= " \n " . ($i + 2) . "- " . $data['Priority_Wise_Count'][$i]['priority'] . " Risk Cases: " . $data['Priority_Wise_Count'][$i]['count'];
        }
        $template_id = "1307166231501132801";
        $mobile_numbers = getenv("mobile_numbers.daily_report");
        $response = submit_unicode_sms($message_unicode, $mobile_numbers, $template_id, true);
        if (check_if_error($response) !== null) {
            log_message("info", "Daily Report SMS Sent: Response is " . $response);
            insert_response($response, $template_id);
        }
    }

}
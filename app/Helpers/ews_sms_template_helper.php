<?php
helper('cdac');

/**
 * @throws ReflectionException
 */
function new_ews_detected_case_sms($mobile_number, $student_id, $student_name)
{
    $message_unicode = $student_name . "(Student ID:" . $student_id . ")दिल्ली सरकार आपके बच्चे की स्कूल में अनुपस्थिति को लेकर चिंतित हैं। हम इस विषय पर आपसे संपर्क करना चाहते हैं। अभी बात करने के लिए कॉल - 01206985700 DCPCR";
    $template_id = "1307165967750122465";
    // {#var#}(Student ID:{#var#}): दिल्ली सरकार आपके बच्चे की स्कूल में अनुपस्थिति को लेकर चिंतित हैं। हम इस विषय पर आपसे संपर्क करना चाहते हैं। अभी बात करने के लिए कॉल - 01206985700 DCPCR
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
    $message_unicode = $student_name . " (Student ID:" . $student_id . "): आशा करते हैं की आपके बच्चे की समस्या हल हो गयी है। बच्चे को रोज़ाना स्कूल भेजें। और कोई समस्या हो तो मदद के लिए कॉल करें- 9311551393 DCPCR";
    // {#var#} (Student ID:{#var#}): आशा करते हैं की आपके बच्चे की समस्या हल हो गयी है। बच्चे को रोज़ाना स्कूल भेजें। और कोई समस्या हो तो मदद के लिए कॉल करें- 9311551393 DCPCR
    $template_id = "1307165967783295949";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id,);
}




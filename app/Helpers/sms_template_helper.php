<?php
function send($message_unicode,$mobile_number,$template_id)
{
    $username = get_cdac_username();
    $password = get_cdac_password();
    $sender_id = get_cdac_senderid();
    $secure_key = get_cdac_securekey();
    send_bulk_unicode($username, $password, $sender_id, $message_unicode, $mobile_number, $secure_key, $template_id);

}
function send_advance_intimation_ews_case_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = "दिल्ली सरकार आपके बच्चे ".$student_name."(Student ID: ".$student_id.") की स्कूल में अनुपस्थिति को लेकर चिंतित हैं। हम इस विषय पर आपसे संपर्क करना चाहते हैं।अभी बात करने के लिए कॉल - 01206985700";
    $template_id = "13245134";
    send($message_unicode,$mobile_number,$template_id,);
}

function call_connected_normal_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = $student_name."(ID:".$student_id.") आपसे बात करके अच्छा लगा।बच्चे को रोजाना स्कूल भेजें। बच्चे से सम्बंधित कोई भी समस्या हो तो मदद के लिए कॉल करें- 01206985700";
    $template_id = "";
    send($message_unicode,$mobile_number,$template_id,);
}

function call_connected_ticket_raised_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = $student_name." (ID:".$student_id."): आपसे बात करके अच्छा लगा,अपने बच्चे को रोजाना स्कूल भेजें।आपकी समस्या दर्ज़ हो गयी है। बच्चों से जुड़ी कोई समस्या हो तो मदद के लिए कॉल करें- 9311551393";
    $template_id = "";
    send($message_unicode,$mobile_number,$template_id,);
}

function not_able_to_contact_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = $student_name." (ID: $student_id): दिल्ली सरकार आपके बच्चे की स्कूल में अनुपस्थिति को लेकर चिंतित है। हम इस विषय पर आपसे संपर्क करना चाहते हैं।अभी बात करने के लिए कॉल - 0120- 6985700";
    $template_id = "";
    send($message_unicode,$mobile_number,$template_id,);
}

function incomplete_information_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = $student_name." (ID:".$student_id."): आपसे बात करके अच्छा लगा। कॉल के दौरान बच्चे की समस्या की पूरी जानकारी नहीं मिल पायी, हम जल्द संपर्क करेंगे।अभी बात करने के लिए कॉल करें- 0120- 6985700";
    $template_id = "";
    send($message_unicode,$mobile_number,$template_id,);

}

function case_closed_sms($mobile_number,$student_id,$student_name)
{
    $message_unicode = $student_name." (ID:".$student_id."): आशा करते हैं की आपके बच्चे की समस्या हल हो गयी है। बच्चे को रोज़ाना स्कूल भेजें। और कोई समस्या हो तो मदद के लिए कॉल करें- 9311551393";
    $template_id = "";
    send($message_unicode,$mobile_number,$template_id,);
}




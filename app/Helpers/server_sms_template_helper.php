<?php
/**
 * @throws ReflectionException
 */
function send_status_sms($message)
{
    helper('cdac');
    $mobile_number= getenv("success_numbers");
    $message_unicode = "Message from DCPCR Early Warning System\n" . $message;
    $template_id = "1307166210199731733";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id);
}
function send_alert_sms($message)
{
    helper('cdac');
    $mobile_number= getenv("alert_numbers");
    $message_unicode = "Message from DCPCR Early Warning System\n" . $message;
    $template_id = "1307166210199731733";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id);
}

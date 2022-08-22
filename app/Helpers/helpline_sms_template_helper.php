<?php
helper('cdac');
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
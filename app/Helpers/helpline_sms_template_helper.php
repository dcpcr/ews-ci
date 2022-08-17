<?php
helper('cdac');
/**
 * @throws ReflectionException
 */
function helpline_new_ticket_sms($ticket_number, $mobile_number)
{

    $template_id = "1307164258584084755";
    $message_unicode = "आपकी शिकायत सफलतापूर्वक रजिस्टर कर ली गयी है टिकट नंबर: $ticket_number काल करने के लिए धन्यवाद। DCPCR Helpline- 9311551393";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id);
}

/**
 * @throws ReflectionException
 */
function helpline_closed_ticket_sms($mobile_number, $ticket_number)
{

    $template_id = "1307164258598813638";
    $message_unicode = "आपकी शिकायत सफलतापूर्वक रजिस्टर कर ली गयी है टिकट नंबर: $ticket_number काल करने के लिए धन्यवाद। DCPCR Helpline- 9311551393";
    return send_single_unicode_sms($message_unicode, $mobile_number, $template_id);
}


/**
 * @throws ReflectionException
 */
function bulk_helpline_promotion_sms($mobile_numbers)
{
    //$mobile_number is an array
    $template_id = "1307162126864296262";
    $message_unicode = "बच्चे को सेहत, पोषण, या कोई और समस्या हो या संबंधित जानकारी चाहिए, तो DCPCR दिल्ली सरकार हेल्पलाइन 9311551393 पर कॉल करें।DCPCR";
    return send_bulk_unicode_sms($message_unicode, $mobile_numbers, $template_id);
}



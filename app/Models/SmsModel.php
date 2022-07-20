<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['message_id', 'status_code'];

    public function __construct()
    {
        parent::__construct();
        helper('general');
    }
    function getMessageId()
    {
        return $this->select('message_id')->where('delivery_report','0')->limit('1')->find();

    }

    function sendSms($username,$password,$sender_id,$messageUnicode,$mobile_nos,$Secure_key,$template_id)
    {
        $smsServerResponseData = sendBulkUnicode($username, $password, $sender_id, $messageUnicode, $mobile_nos, $Secure_key, $template_id);
        $smsdata = explode('=', $smsServerResponseData);
        $message_id = $smsdata['1'];
        $status_code = explode(',', $smsServerResponseData);
        $status_code = $status_code['0'];
        $responseData = [
            "message_id" => "$message_id",
            "status_code" => "$status_code"

        ];
        $this->ignore(true)->insert($responseData);
    }

    function checkSmsStatus()
    {
        //
    }
}

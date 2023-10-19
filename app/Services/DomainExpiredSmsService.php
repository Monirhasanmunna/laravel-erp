<?php

namespace App\Services;

use App\Helper\Helper;
use Illuminate\Support\Facades\Log;


class DomainExpiredSmsService
{
    public function sendSms($array){
        $msg = '';
        $msg .= "Dear Sir,";
        $msg .= "Your Website & Software ".$array['institute']['domain']." will be down ".$array['institute']['expire_date'].".";
        $msg .= "Please Pay the dues amount within ".$array['institute']['expire_date']." or contact our support team.";
        $msg .= "EDTECO EMIS.";

        Helper::sd_send_sms_api($array['institute']['phone'],$msg);
        $this->sendAdmin($msg);
        $this->sendSeller($array['institute'],$msg);
    }

    public function sendAdmin($message): void
    {
        Helper::sd_send_sms_api("01301393735",$message);
    }
    public function sendSeller($ins,$message): void
    {
        Helper::sd_send_sms_api($ins['seller']['phone'],$message);
    }
}

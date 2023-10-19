<?php

namespace App\Traits;

trait SmsContentCount
{
    public function smsContentCount($message){

        $count =  strlen($message);

        if($count > 60){
            $smsCount = 2;
        }
        elseif($count > 120){
            $smsCount = $count/60;
        }
        else{
            $smsCount = 1;
        }

        return (int) round($smsCount);

    }
}
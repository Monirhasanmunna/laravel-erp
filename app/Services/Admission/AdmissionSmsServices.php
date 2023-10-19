<?php

namespace App\Services\Admission;
use App\Helper\Helper;
use App\Jobs\SmsSendJob;
use App\Models\Notification;

Class AdmissionSmsServices{
    public function confirmSms($student)
    {
        $template = Notification::where('type','confirm-admission')->first();
        $branch = @$template->branch;
        $smsContent = '';

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;
        
        if($stock_sms > 0 && @$template->status == true && @$branch){
            $content            = $template->content;
            $replace            = [":Name", ":Roll"];
            $replaceItem        = [$student['name'], $student['roll_no']];
            $smsContent         = str_replace($replace, $replaceItem, $content);

             SmsSendJob::dispatch('Admission Confirm Sms Send',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
            
        }
    }

    public function admissionSubmitSms($student)
    {
        $template = Notification::where('type','admission')->first();
        $branch = @$template->branch;
        $smsContent = '';

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;
        
        if($stock_sms > 0 && @$template->status == true && @$branch){
            $content            = $template->content;
            $replace            = [":Name", ":admissionId"];
            $replaceItem        = [$student['name'], $student['admission_no']];
            $smsContent         = str_replace($replace, $replaceItem, $content);

             SmsSendJob::dispatch('Admission Submit Sms Send',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
            
        }
    }
}
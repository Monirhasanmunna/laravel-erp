<?php

namespace App\Services\Sms;

use App\Helper\Helper;
use App\Models\Institution;
use App\Models\SmsHistory;
use App\Models\StockSms;
use App\Traits\SmsContentCount;
use Illuminate\Support\Facades\Log;

class SmsService
{
    use SmsContentCount;

    private $apiKey;
    private $secretKey;
    private $senderId;

    private $instituteId;
    private $branchId;

    public function __construct($instituteId,$branchId)
    {
        $this->apiKey    = config('services.sms.api_key');
        $this->secretKey = config('services.sms.secret_key');
        $this->senderId  = config('services.sms.sender_id');
        $this->instituteId = $instituteId;
        $this->branchId = $branchId;
    }

    public function sendSms($title,$number,$message){
        
        $matched    = ['institute_id'=> $this->instituteId,'institute_branch_id' => $this->branchId];
        $smsBalance = StockSms::withoutGlobalScopes()->where($matched)->first();
  
        $curl = curl_init();
        
        if(@$smsBalance->currentBalance > 0){
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://188.138.41.146:7788/sendtext?apikey='. $this->apiKey .'&secretkey=' . $this->secretKey . '&callerID=' . $this->senderId . '&toUser=' . $number . '&messageContent=' . urlencode($message),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
    
            $response = curl_exec($curl);
            $responseToJson = json_decode($response, true);
            curl_close($curl);
          
            if( $responseToJson['Text'] == "ACCEPTD"){
                $smsCount = $this->smsContentCount($message);
                $status = 1;
                $this->smsStockUpdate($smsCount);
                $this->smsHistoryStore($title,$message,$smsCount,$number,$status);
            }
            else{
                $smsCount = $this->smsContentCount($message);
                $status = 0;
                $this->smsHistoryStore($title,$message,$smsCount,$number,$status);
            }
        }
    }


    public function smsStockUpdate($smsCount){

        $matched  = ['institute_id'=> $this->instituteId,'institute_branch_id' => $this->branchId];
        $stockSms = StockSms::withoutGlobalScopes()->where($matched)->first();

        $stockSms->update([
            'currentBalance' => $stockSms->currentBalance - $smsCount,
            'total_spend'    => $stockSms->total_spend + $smsCount
        ]);
    }


    public function smsHistoryStore($title,$message,$smsCount,$number,$status){

        SmsHistory::create([
            'institute_id'        => $this->instituteId,
            'institute_branch_id' => $this->branchId,
            'title'               => $title,
            'description'         => $message,
            'sms_count'           => $smsCount,
            'number'              => $number,
            'time'                => date("Y-m-d H:i:s"),
            'status'              => $status
        ]);
    }


}

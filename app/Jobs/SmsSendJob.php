<?php

namespace App\Jobs;

use App\Services\Sms\SmsService;
use App\Services\SmsSendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SmsSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $title;
    private $instituteId;
    private $branchId;
    private $number;
    private $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$instituteId,$branchId,$number,$message)
    {
        $this->title = $title;
        $this->instituteId = $instituteId;
        $this->branchId = $branchId;
        $this->number  = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsService = new SmsService($this->instituteId,$this->branchId);
        $smsService->sendSms($this->title,$this->number,$this->message);
    }
}

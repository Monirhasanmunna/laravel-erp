<?php

namespace App\Console\Commands;

use App\Services\SmsSendService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PresentSmsSend extends Command
{
    private $service;

    public function __construct(SmsSendService $smsSendService)
    {
        parent::__construct();
        $this->service = $smsSendService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'present-sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Present Sms Send Successfully';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $this->service->sendPresentSms("Present Sms");
    }
}

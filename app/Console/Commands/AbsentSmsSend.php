<?php

namespace App\Console\Commands;
use App\Services\SmsSendService;
use Illuminate\Console\Command;

class AbsentSmsSend extends Command
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
    protected $signature = 'absent-sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->service->sendAbsentSms();
    }
}

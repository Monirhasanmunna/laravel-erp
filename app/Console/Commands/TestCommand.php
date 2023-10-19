<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Jobs\SmsSendJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is Test Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        SmsSendJob::dispatch("Testing",4,6,"01683813854","This is Demo Sms From Edteco");

    }
}

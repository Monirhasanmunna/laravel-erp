<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\ApiSmsCheck;
use Illuminate\Console\Command;

class OldSmsRecordDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldsms:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Old Sms Record Delete';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oldSms = ApiSmsCheck::all();
        foreach ($oldSms as $key => $sms) {
            $sms->delete();
        }
    }
}

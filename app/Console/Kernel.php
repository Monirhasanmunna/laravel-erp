<?php

namespace App\Console;

use App\Helper\Helper;
use App\Models\ApiAutoSmsSend;
use App\Models\Teacher;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ExpiredSmsSend::class,
        Commands\AutoSmsSend::class,
        Commands\PresentSmsSend::class,
        Commands\AbsentSmsSend::class,
        Commands\TestCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('present-sms:send')->timezone('Asia/Dhaka')->everyMinute();
        $schedule->command('absent-sms:send')->timezone('Asia/Dhaka')->everyMinute();
       // $schedule->command('absent-sms:send')->timezone('Asia/Dhaka')->between('9:00', '17:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

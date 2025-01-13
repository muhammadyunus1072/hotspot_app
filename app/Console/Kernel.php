<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');

        // return env('MAIL_FROM_ADDRESS', 'hello@example.com');
        $to = 'arashiyunus@gmail.com'; // Use your verified email
        // Mail::raw('This is a test email from Amazon SES in Sydney (ap-southeast-2) region.', function ($message) use ($to) {
        //     $message->to($to)
        //             ->subject('Test Email from SES');
        // });
    }
}

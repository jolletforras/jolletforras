<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        //!!!addig nem működik a send-notice-emails míg a sendemails táblában bejegyzés van !!!!!!!!!!

        //$schedule->command('send-email-new-group-theme')->cron('* * * * *');
        //egyszerre kell a kettőt bekapcsolni!!!!
        /*$schedule->command('send-stored-emails')->everyMinute()->withoutOverlapping();
        $schedule->command('send-notice-emails')->everyMinute()->withoutOverlapping();*/

        //$schedule->command('adjust-user-notice-counter')->everyMinute()->withoutOverlapping();
        //$schedule->command('adjust-group-notice-counter')->everyMinute()->withoutOverlapping();

        $schedule->command('send-notice-emails')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('send-stored-emails')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('adjust-user-notice-counter')->dailyAt('01:00')->withoutOverlapping();
        $schedule->command('adjust-group-notice-counter')->dailyAt('01:20')->withoutOverlapping();
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

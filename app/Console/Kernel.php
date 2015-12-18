<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\DailyDbBackup::class,
        \App\Console\Commands\ClearOldBackdateEntries::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('backup:dbdaily')->dailyAt('16:00'); // IST 9:30 PM
        $schedule->command('backup:dbdaily')->dailyAt('10:30'); // IST 2:30 PM
        //$schedule->command('clear-backdates')->daily(); // this will run @ midnight everyday
    }
}

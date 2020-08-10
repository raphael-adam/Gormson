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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* Command for getting and parsing timetape data and storing it */
        $schedule->command('command:IcsDataCommand')
            ->hourly()
            ->weekdays();

        /* Command sending updates */
        $schedule->command('command:AbsenceUpdateInfoCommand')
            ->hourly()
            ->between('6:00', '18:00')
            ->weekdays();

        $schedule->command('command:AbsenceMondayInfoCommand')
            ->dailyAt('13:00')
            ->fridays();

        $schedule->command('command:AbsenceInfoCommand')
            ->dailyAt('6:00')
            ->weekdays();
    }

    //Vertrettung verlinken im Chat


    //
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

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
        \App\Console\Commands\Nightly::class,
        \App\Console\Commands\NightlyVerify::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (\App::environment('prod')) {
            //$schedule->command('backup:clean')->weekly()->mondays()->at('00:01');
            //$schedule->command('backup:run')->daily()->at('00:02');
            $schedule->command('app:nightly')->daily()->at('00:05');
            $schedule->command('app:nightly-verify')->daily()->at('00:30');
        }
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

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
        Commands\PostDailyLisData::class,
        Commands\PostLisData::class,
        Commands\PostDailyPublicHealth::class,
        Commands\PostPeriodPublicHealth::class,
        Commands\AutoReviewed::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('postlis:daily')
            ->dailyAt('18:30');
        $schedule->command('posthealth:daily')
            ->dailyAt('20:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // require base_path('routes/console.php');
    }
}

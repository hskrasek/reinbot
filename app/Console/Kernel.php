<?php

namespace App\Console;

use App\Console\Commands\PostMilestones;
use App\Console\Commands\RefreshTokens;
use App\Console\Commands\UpdateManifest;
use App\Console\Commands\XurInventory;
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
        PostMilestones::class,
        UpdateManifest::class,
        XurInventory::class,
        RefreshTokens::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('destiny:milestones')
            ->weeklyOn(2, '12:30')
            ->timezone('America/Chicago')
            ->withoutOverlapping();

        $schedule->command('destiny:manifest')
            ->hourly()
            ->timezone('America/Chicago')
            ->withoutOverlapping();

        $schedule->command('destiny:xur')
            ->weeklyOn(5, '12:05')
            ->timezone('America/Chicago')
            ->withoutOverlapping();

        $schedule->command('destiny:refresh-tokens')
            ->weeklyOn(5, '10:50')
            ->timezone('America/Chicago')
            ->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

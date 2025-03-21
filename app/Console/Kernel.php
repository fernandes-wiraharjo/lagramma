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
        $schedule->command('app:sync-moka-categories')->everyThirtyMinutes();
        $schedule->command('app:sync-moka-modifiers')->everyThirtyMinutes();
        $schedule->command('app:sync-moka-sales-types')->everyThirtyMinutes();
        $schedule->command('app:sync-moka-products')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

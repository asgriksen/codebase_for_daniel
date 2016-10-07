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
        \App\Console\Commands\EnforceRules::class,
        \App\Console\Commands\ScrapeAccount::class,
        \App\Console\Commands\SyncProducts::class,
        \App\Console\Commands\RefreshGoogleAccessTokens::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Enforce rules (daily for now)
        $schedule->command('enforce:rules')->daily();

        // Sync the product every hour.
        $schedule->command('sync:products')->hourly();

        // Refresh Google access tokens.
        $schedule->command('refresh:google-access-tokens')->everyFiveMinutes();
    }
}

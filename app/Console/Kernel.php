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
        // Automatic Backups
        $schedule->command('backup:run --type=hourly')->hourly();
        $schedule->command('backup:run --type=daily')->dailyAt('02:00');
        $schedule->command('backup:run --type=weekly')->weeklyOn(0, '03:00');
        $schedule->command('backup:run --type=monthly')->monthlyOn(1, '04:00');

        // Backup Cleanup (retain 30 days of daily, 90 days of weekly)
        $schedule->command('backup:cleanup --days=30')->daily();

        // Business Checks
        $schedule->command('installments:check-overdue')->dailyAt('08:00');
        $schedule->command('stock:check-low')->dailyAt('08:30');
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

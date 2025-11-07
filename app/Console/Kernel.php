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
        // Send pre-trip reminders
        $schedule->command('bookings:send-pre-trip-reminders')
            ->daily()
            ->at('09:00');

        // Send payment reminders
        $schedule->command('bookings:send-payment-reminders')
            ->daily()
            ->at('10:00');

        // Send post-trip follow-ups
        $schedule->command('bookings:send-post-trip-followups')
            ->daily()
            ->at('11:00');
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


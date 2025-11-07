<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\PreTripReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPreTripReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-pre-trip-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pre-trip reminder emails to customers';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending pre-trip reminders...');

        // Send 7-day reminders
        $this->sendReminders(7, '7 days');
        
        // Send 3-day reminders
        $this->sendReminders(3, '3 days');
        
        // Send 1-day reminders
        $this->sendReminders(1, '1 day');

        $this->info('Pre-trip reminders sent successfully!');
        return Command::SUCCESS;
    }

    private function sendReminders(int $daysBefore, string $label): void
    {
        $targetDate = now()->addDays($daysBefore)->format('Y-m-d');
        
        $bookings = Booking::with(['customer', 'package'])
            ->where('start_date', $targetDate)
            ->where('status', 'confirmed')
            ->whereDoesntHave('payments', function ($query) use ($daysBefore) {
                // Check if reminder already sent for this day
                $query->where('payment_status', 'reminder_sent_' . $daysBefore);
            })
            ->get();

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->customer->email)
                    ->send(new PreTripReminder($booking, $daysBefore));
                
                Log::info("Pre-trip reminder ({$label}) sent", [
                    'booking_reference' => $booking->booking_reference,
                    'customer_email' => $booking->customer->email,
                ]);
                
                $this->line("Sent {$label} reminder to {$booking->customer->email} for booking {$booking->booking_reference}");
            } catch (\Exception $e) {
                Log::error("Failed to send pre-trip reminder: " . $e->getMessage(), [
                    'booking_reference' => $booking->booking_reference,
                ]);
                $this->error("Failed to send reminder to {$booking->customer->email}");
            }
        }
    }
}


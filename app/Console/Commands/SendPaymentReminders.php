<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\PaymentReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails for pending bookings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending payment reminders...');

        // Get pending bookings with balance
        $bookings = Booking::with(['customer', 'package'])
            ->where('status', 'pending')
            ->where('total_amount', '>', 0)
            ->get()
            ->filter(function ($booking) {
                return $booking->balance > 0;
            });

        foreach ($bookings as $booking) {
            $daysSinceBooking = $booking->created_at->diffInDays(now());
            
            // Send reminders at 3, 7, and 14 days
            if (in_array($daysSinceBooking, [3, 7, 14])) {
                try {
                    Mail::to($booking->customer->email)
                        ->send(new PaymentReminder($booking, $daysSinceBooking));
                    
                    Log::info("Payment reminder sent ({$daysSinceBooking} days)", [
                        'booking_reference' => $booking->booking_reference,
                        'customer_email' => $booking->customer->email,
                        'balance' => $booking->balance,
                    ]);
                    
                    $this->line("Sent {$daysSinceBooking}-day reminder to {$booking->customer->email} for booking {$booking->booking_reference}");
                } catch (\Exception $e) {
                    Log::error("Failed to send payment reminder: " . $e->getMessage(), [
                        'booking_reference' => $booking->booking_reference,
                    ]);
                    $this->error("Failed to send reminder to {$booking->customer->email}");
                }
            }
        }

        $this->info('Payment reminders sent successfully!');
        return Command::SUCCESS;
    }
}


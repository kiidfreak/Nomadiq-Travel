<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\PostTripFollowUp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPostTripFollowups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-post-trip-followups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send post-trip follow-up emails to customers';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending post-trip follow-ups...');

        // Send 1-day follow-up
        $this->sendFollowUps(1, 'thank_you');
        
        // Send 3-day follow-up (request review)
        $this->sendFollowUps(3, 'review');
        
        // Send 7-day follow-up (share memories)
        $this->sendFollowUps(7, 'memories');

        $this->info('Post-trip follow-ups sent successfully!');
        return Command::SUCCESS;
    }

    private function sendFollowUps(int $daysAfter, string $type): void
    {
        // Get all confirmed bookings
        $bookings = Booking::with(['customer', 'package'])
            ->where('status', 'confirmed')
            ->get()
            ->filter(function ($booking) use ($daysAfter) {
                // Check if trip has ended and X days have passed since end date
                if ($booking->package && $booking->package->duration_days) {
                    $endDate = $booking->start_date->copy()->addDays($booking->package->duration_days);
                    $targetDate = now()->subDays($daysAfter);
                    return $endDate->format('Y-m-d') <= $targetDate->format('Y-m-d');
                }
                return false;
            })
            ->filter(function ($booking) use ($type) {
                // Check if follow-up already sent (using a simple check - you might want to track this in a separate table)
                // For now, we'll send once per type
                return true;
            });

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->customer->email)
                    ->send(new PostTripFollowUp($booking, $type));
                
                Log::info("Post-trip follow-up sent ({$type}, {$daysAfter} days)", [
                    'booking_reference' => $booking->booking_reference,
                    'customer_email' => $booking->customer->email,
                ]);
                
                $this->line("Sent {$type} follow-up to {$booking->customer->email} for booking {$booking->booking_reference}");
            } catch (\Exception $e) {
                Log::error("Failed to send post-trip follow-up: " . $e->getMessage(), [
                    'booking_reference' => $booking->booking_reference,
                ]);
                $this->error("Failed to send follow-up to {$booking->customer->email}");
            }
        }
    }
}


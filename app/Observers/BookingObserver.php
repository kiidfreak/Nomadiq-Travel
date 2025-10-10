<?php

namespace App\Observers;

use App\Models\Booking;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        // Optionally send welcome email when booking is first created
        if ($booking->status === 'pending') {
            $this->sendBookingCreatedEmail($booking);
        }
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if status was changed
        if ($booking->isDirty('status')) {
            $oldStatus = $booking->getOriginal('status');
            $newStatus = $booking->status;
            
            Log::info("Booking {$booking->booking_reference} status changed from {$oldStatus} to {$newStatus}");
            
            // Send different emails based on status change
            switch ($newStatus) {
                case 'confirmed':
                    $this->sendBookingConfirmationEmail($booking);
                    break;
                case 'cancelled':
                    $this->sendBookingCancellationEmail($booking);
                    break;
            }
        }
    }

    /**
     * Send booking created email
     */
    private function sendBookingCreatedEmail(Booking $booking): void
    {
        try {
            $booking->load(['customer', 'package']);
            
            Mail::to($booking->customer->email)
                ->send(new BookingConfirmation($booking, 'created'));
                
            Log::info("Booking created email sent to {$booking->customer->email} for booking {$booking->booking_reference}");
        } catch (\Exception $e) {
            Log::error("Failed to send booking created email: " . $e->getMessage());
        }
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail(Booking $booking): void
    {
        try {
            $booking->load(['customer', 'package']);
            
            Mail::to($booking->customer->email)
                ->send(new BookingConfirmation($booking, 'confirmed'));
                
            Log::info("Booking confirmation email sent to {$booking->customer->email} for booking {$booking->booking_reference}");
        } catch (\Exception $e) {
            Log::error("Failed to send booking confirmation email: " . $e->getMessage());
        }
    }

    /**
     * Send booking cancellation email
     */
    private function sendBookingCancellationEmail(Booking $booking): void
    {
        try {
            $booking->load(['customer', 'package']);
            
            Mail::to($booking->customer->email)
                ->send(new BookingConfirmation($booking, 'cancelled'));
                
            Log::info("Booking cancellation email sent to {$booking->customer->email} for booking {$booking->booking_reference}");
        } catch (\Exception $e) {
            Log::error("Failed to send booking cancellation email: " . $e->getMessage());
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}

<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreTripReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public int $daysBefore
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->daysBefore) {
            7 => 'Your Nomadiq Adventure Starts in 7 Days!',
            3 => 'Your Nomadiq Adventure Starts in 3 Days!',
            1 => 'Your Nomadiq Adventure Starts Tomorrow!',
            default => 'Pre-Trip Reminder - ' . $this->booking->booking_reference,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pre-trip-reminder',
            with: [
                'daysBefore' => $this->daysBefore,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


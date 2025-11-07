<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public int $daysSince
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $urgency = match($this->daysSince) {
            3 => 'Friendly Reminder',
            7 => 'Important Reminder',
            14 => 'Final Reminder',
            default => 'Payment Reminder',
        };

        return new Envelope(
            subject: $urgency . ' - Complete Your Nomadiq Booking Payment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'daysSince' => $this->daysSince,
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


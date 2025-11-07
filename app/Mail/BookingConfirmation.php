<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public string $type = 'confirmed'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'created' => 'Booking Received - ' . $this->booking->booking_reference,
            'confirmed' => 'Booking Confirmed - ' . $this->booking->booking_reference,
            'cancelled' => 'Booking Cancelled - ' . $this->booking->booking_reference,
            default => 'Booking Update - ' . $this->booking->booking_reference,
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
            view: 'emails.booking-confirmation',
            with: [
                'emailType' => $this->type
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

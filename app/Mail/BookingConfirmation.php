<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;   // ← add this
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable implements ShouldQueue   // ← add implements
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmed - ' . $this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-confirmation',
            with: [
                'booking' => $this->booking,
                'user'    => $this->booking->user,
                'movie'   => $this->booking->movie,
            ],
        );
    }
}
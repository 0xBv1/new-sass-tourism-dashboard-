<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function envelope(): Envelope
    {
        $type = match($this->appointment->type) {
            'nile_cruise' => 'Nile Cruise',
            'plan_trip' => 'Trip Planning',
            default => 'Appointment',
        };

        return new Envelope(
            subject: "New {$type} Request - {$this->appointment->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-notification',
            with: [
                'appointment' => $this->appointment,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


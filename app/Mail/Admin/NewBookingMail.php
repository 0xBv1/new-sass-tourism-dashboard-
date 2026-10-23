<?php

namespace App\Mail\Admin;

use App\Enums\SettingKey;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Booking $booking;
    /**
     * @var array|mixed|null
     */
    private mixed $social_links;

    public function __construct(Booking $booking)
    {
        $this->social_links = Setting::key(SettingKey::SOCIAL_LINKS->value)->first()?->option_value;
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-booking',
            with: [
                'booking' => $this->booking,
                'social_links' => $this->social_links,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

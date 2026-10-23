<?php

namespace App\Mail\Client;

use App\Enums\SettingKey;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewBookingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    /**
     * @var array|mixed|null
     */
    private mixed $social_links;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->social_links = Setting::key(SettingKey::SOCIAL_LINKS->value)->first()?->option_value;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Receipt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client.new-booking',
            with: [
                'booking' => $this->booking,
                'social_links' => $this->social_links,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        $paths = [];

        foreach ($this->booking->tours as $tour) {
            $attachment = $tour->asAttachment($this->booking);

            if ($attachment) {
                $paths[] = Str::of($attachment)->remove(storage_path('app/public'));
                $attachments[] = Attachment::fromPath($attachment)
                    ->as($tour->title . '.pdf')
                    ->withMime('application/pdf');
            }
        }

        $meta = $this->booking->meta;
        $meta['attachments'] = $paths;
        $this->booking->update([
            'meta' => $meta
        ]);

        return $attachments;
    }


}

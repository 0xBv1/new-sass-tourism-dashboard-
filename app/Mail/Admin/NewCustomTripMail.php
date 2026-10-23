<?php

namespace App\Mail\Admin;

use App\Models\CustomTrip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class NewCustomTripMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public CustomTrip $customTrip;
    private string $mail_template_path;

    public function __construct(CustomTrip $customTrip)
    {
        $this->customTrip = $customTrip;
        $this->mail_template_path = 'emails.admin.custom-trips.' . Str::of($this->customTrip->type)->slug();
//        if (!$this->customTrip->relationLoaded('categories')) {
//            $this->customTrip->load('categories');
//        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Custom Trip Request',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->mail_template_path,
            with: [
                'trip' => $this->customTrip,
                'operator' => null
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

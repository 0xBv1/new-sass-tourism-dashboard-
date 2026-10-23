<?php

namespace App\Mail\Admin;

use App\Models\CustomTrip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AssignedCustomTripMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public CustomTrip $customTrip;
    private string $mail_template_path;
    private ?User $operator;

    public function __construct(CustomTrip $customTrip, User|null $operator= null)
    {
        $this->customTrip = $customTrip;
        $this->mail_template_path = 'emails.admin.custom-trips.' . Str::of($this->customTrip->type)->slug();
        if (!$this->customTrip->relationLoaded('categories')) {
            $this->customTrip->load('categories');
        }
        $this->operator = $operator;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Custom Trip Request Assign',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->mail_template_path,
            with: [
                'trip' => $this->customTrip,
                'operator' => $this->operator,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Notifications\Admin;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ContactRequest $contactRequest;

    public function __construct(ContactRequest $contactRequest)
    {
        $this->contactRequest = $contactRequest;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello Admin,')
            ->subject('Contact Request - ' . $this->contactRequest->subject)
            ->line('You have a new contact request.')
            ->line('Name: ' . $this->contactRequest->name)
            ->line('Phone: ' . $this->contactRequest->phone)
            ->line('Email: ' . $this->contactRequest->email)
            ->line('Country: ' . $this->contactRequest->country)
            ->line('Subject: ' . $this->contactRequest->subject)
            ->line('Message: ' . $this->contactRequest->message)
            ->action('View', route('dashboard.contact-requests.index'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}

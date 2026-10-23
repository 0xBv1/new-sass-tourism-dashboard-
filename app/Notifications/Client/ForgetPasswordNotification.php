<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
        $this->locale = app()->getLocale();
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('messages.notifications.password.forget.otp'))
            ->greeting(__('messages.notifications.greeting', ['name' => $notifiable->name]))
            ->line(__('messages.notifications.password.forget.request_received'))
            ->line(__('messages.notifications.password.forget.dont_worry'))
            ->line(__('messages.notifications.password.forget.request_otp', ['otp' => $this->otp]))
            ->line(__('messages.notifications.password.forget.ignore_mail'))
            ->line(__('messages.notifications.thanks_for_using_our_app'));
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}

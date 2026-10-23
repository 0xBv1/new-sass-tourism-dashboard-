<?php

namespace App\Channels;

use App\Services\Whatsapp\WhatsappMessaging;
use Illuminate\Notifications\Notification;

class WhatsappChannel
{
    private string $method = 'toWhatsapp';

    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification): bool
    {
        $data = $notification->{$this->method}($notifiable);
        $whatsappMessingService = new WhatsappMessaging;
        return $whatsappMessingService->send(
            to: $data['to'] ?? null,
            template: $data['template'] ?? null,
            placeholders: $data['placeholders'] ?? [],
            langCode: $data['language'] ?? 'en_US'
        );
    }
}

<?php

namespace App\Notifications\Admin;

use App\Models\CustomTrip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AssignedCustomTripNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private CustomTrip $customTrip;

    public function __construct(CustomTrip $customTrip)
    {
        $this->customTrip = $customTrip;
    }

    public function via($notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsapp($notifiable): array
    {
        $placeholders = [
            [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $notifiable->name
                    ]
                ]
            ],

            [
                "type" => "button",
                "sub_type" => "url",
                "index" => "0",
                "parameters" => [
                    [
                        "type" => "payload",
                        "payload" => $this->customTrip->id
                    ]
                ]
            ]
        ];
        return [
            'to' => $notifiable->phone_code . $notifiable->phone,
            'template' => 'assigned_custom_trip',
            'placeholders' => $placeholders
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}

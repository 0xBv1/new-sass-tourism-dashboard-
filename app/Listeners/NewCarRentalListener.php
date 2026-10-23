<?php

namespace App\Listeners;

use App\Enums\SettingKey;
use App\Events\NewCarRentalEvent;
use App\Notifications\Admin\NewCarRentalNotification as AdminNotification;
use App\Notifications\Client\NewCarRentalNotification as ClientNotification;
use Illuminate\Support\Facades\Notification;

class NewCarRentalListener
{
    public function __construct()
    {
    }

    public function handle(NewCarRentalEvent $event): void
    {
        Notification::route('mail', $event->carRental->email)
            ->notify(new ClientNotification($event->carRental));

        try_exec(function () use ($event) {
            $emails = setting(SettingKey::NOTIFICATION_EMAILS->value);
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    Notification::route('mail', $email)
                        ->notify(new AdminNotification($event->carRental));

                }
            }
        });
    }
}

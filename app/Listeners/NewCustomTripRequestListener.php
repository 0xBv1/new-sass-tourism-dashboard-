<?php

namespace App\Listeners;

use App\Enums\SettingKey;
use App\Events\NewCustomTripRequestEvent;
use App\Mail\Admin\NewCustomTripMail;
use Illuminate\Support\Facades\Mail;

class NewCustomTripRequestListener
{
    public function __construct()
    {
    }

    public function handle(NewCustomTripRequestEvent $event): void
    {
        $emails = setting(SettingKey::NOTIFICATION_EMAILS->value);

        if (!empty($emails)) {
            Mail::to($emails)->send(New NewCustomTripMail($event->customTrip));
        }
    }
}

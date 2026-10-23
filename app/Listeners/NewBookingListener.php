<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\SettingKey;
use App\Events\NewBookingEvent;
use App\Mail\Admin\NewBookingMail as AdminBookingMail;
use App\Mail\Client\NewBookingMail as ClientBookingMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class NewBookingListener
{
    public function __construct()
    {
    }

    public function handle(NewBookingEvent $event): void
    {
        $clientEmail = $event->booking->client?->email ?? $event->booking->email;

        if ($clientEmail && $event->booking->payment_status == PaymentStatus::PAID->value) {
            try_exec(callable: fn() => Mail::to(trim($clientEmail))->send(new ClientBookingMail($event->booking)));
        }

        try_exec(callable: function () use ($event) {
            $notifiableAdmins = Setting::key(SettingKey::NOTIFICATION_EMAILS->value)->first()?->option_value;
            if (!empty($notifiableAdmins)) {
                $notifiableAdmins = array_map(fn($email) => trim($email), $notifiableAdmins);
                $notifiableAdmins = array_unique($notifiableAdmins);
                Mail::to($notifiableAdmins)->send(new AdminBookingMail($event->booking));
            }
        });
    }
}

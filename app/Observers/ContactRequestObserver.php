<?php

namespace App\Observers;

use App\Enums\SettingKey;
use App\Models\ContactRequest;
use App\Notifications\Admin\ContactRequestNotification;
use Illuminate\Support\Facades\Notification;

class ContactRequestObserver
{
    /**
     * Handle the ContactRequest "created" event.
     *
     * @param ContactRequest $contactRequest
     * @return void
     * @throws \Throwable
     */
    public function created(ContactRequest $contactRequest): void
    {
        try_exec(function () use ($contactRequest) {
            $emails = setting(SettingKey::NOTIFICATION_EMAILS->value);
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    Notification::route('mail', $email)
                        ->notify(new ContactRequestNotification($contactRequest));

                }
            }
        });
    }

    /**
     * Handle the ContactRequest "updated" event.
     *
     * @param ContactRequest $contactRequest
     * @return void
     */
    public function updated(ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Handle the ContactRequest "deleted" event.
     *
     * @param ContactRequest $contactRequest
     * @return void
     */
    public function deleted(ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Handle the ContactRequest "restored" event.
     *
     * @param ContactRequest $contactRequest
     * @return void
     */
    public function restored(ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Handle the ContactRequest "force deleted" event.
     *
     * @param ContactRequest $contactRequest
     * @return void
     */
    public function forceDeleted(ContactRequest $contactRequest)
    {
        //
    }
}

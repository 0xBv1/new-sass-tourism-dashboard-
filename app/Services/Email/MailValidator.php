<?php

namespace App\Services\Email;

use App\Models\EmailStatus;

class MailValidator
{
    public static function validate(string $email)
    {
        $email_cache = EmailStatus::where('email', $email)->first();

        if ($email_cache) {
            return $email_cache->status;
        }

        return EmailStatus::DELIVERABLE;
    }
}

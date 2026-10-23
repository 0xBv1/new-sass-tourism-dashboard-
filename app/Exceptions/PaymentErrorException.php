<?php

namespace App\Exceptions;

use Exception;

class PaymentErrorException extends Exception
{
    public function __construct(string|array $message = "")
    {
        if (is_array($message)) {
            $message = array_values($message)[0];
        }
        parent::__construct(__('messages.bookings.payment-error', ['message' => $message]));
    }
}

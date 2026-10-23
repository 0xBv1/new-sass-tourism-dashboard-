<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidPaymentGateWayException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = __('messages.payment.invalid-gateway');
        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EmptyCartException extends Exception
{
    public function __construct( string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = __('messages.bookings.empty-cart');
        parent::__construct($message, $code, $previous);
    }
}

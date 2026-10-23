<?php

namespace App\Exceptions;

use Exception;

class NoCarRouteAvailable extends Exception
{
    public function __construct()
    {
        $message = __('messages.car-rental.not-found');
        parent::__construct($message);
    }
}

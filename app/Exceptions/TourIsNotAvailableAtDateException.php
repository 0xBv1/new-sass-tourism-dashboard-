<?php

namespace App\Exceptions;

use Exception;

class TourIsNotAvailableAtDateException extends Exception
{
    public function __construct(string $date = "")
    {
        parent::__construct(__('messages.cart.tour-not-available', ['date' => $date]));
    }
}

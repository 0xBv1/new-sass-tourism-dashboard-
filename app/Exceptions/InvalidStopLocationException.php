<?php

namespace App\Exceptions;

use App\Traits\Response\HasApiResponse;
use Exception;

class InvalidStopLocationException extends Exception
{
    use HasApiResponse;

    public function __construct($locationName = null)
    {
        $message = __('messages.car-rental.invalid-stop-location', ['location_name' => $locationName]);
        parent::__construct($message);
    }
}

<?php

namespace App\Exceptions;

use App\Traits\Response\HasApiResponse;
use Exception;

class NoAvailableCarForRouteException extends Exception
{
    use HasApiResponse;

    public function __construct()
    {
        $message = __('messages.car-rental.no-price-group-found');
        parent::__construct($message);
    }
}

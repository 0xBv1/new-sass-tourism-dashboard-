<?php

namespace App\Exceptions;

use Exception;

class CouponNotAvailableForSelectedToursException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('messages.coupons.invalid-tours'));
    }
}

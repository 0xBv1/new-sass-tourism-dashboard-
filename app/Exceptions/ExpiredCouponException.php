<?php

namespace App\Exceptions;

use Exception;

class ExpiredCouponException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('messages.coupons.expired'));
    }
}

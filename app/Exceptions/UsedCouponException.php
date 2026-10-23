<?php

namespace App\Exceptions;

use Exception;

class UsedCouponException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('messages.coupons.not_available'));
    }
}

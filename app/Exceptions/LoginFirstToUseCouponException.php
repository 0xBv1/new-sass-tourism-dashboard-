<?php

namespace App\Exceptions;

use Exception;

class LoginFirstToUseCouponException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('messages.coupons.login-first-to-use-coupon'));
    }
}

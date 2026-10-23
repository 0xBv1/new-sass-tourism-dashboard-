<?php

namespace App\Payments;

use App\Exceptions\InvalidPaymentGateWayException;
use Throwable;


class PaymentFactory
{
    /**
     * @throws InvalidPaymentGateWayException|Throwable
     */
    public static function instance($type): PaymentGateway
    {
        $gateway = 'App\Payments\Gateways\\' . \Str::studly($type);

        throw_unless(class_exists($gateway), new InvalidPaymentGateWayException);

        return new $gateway;
    }
}

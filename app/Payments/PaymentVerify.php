<?php

namespace App\Payments;

interface PaymentVerify
{
    public function verify(array $options = []);
}

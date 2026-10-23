<?php

namespace App\Payments;

use App\Models\Booking;

interface PaymentGateway
{
    public function setBooking(Booking $booking): self;

    public function pay(Booking $booking): void;

    public function redirect(): array;

    public function message(): string;

}

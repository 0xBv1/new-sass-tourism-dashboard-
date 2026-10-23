<?php

namespace App\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Payments\PaymentGateway;

class Cash implements PaymentGateway
{
    private Booking $booking;

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;
        return $this;
    }

    public function pay(Booking $booking): void
    {
        $this->setBooking($booking);
        $this->booking->payment()->create([
            'gateway' => PaymentMethod::COD->value
        ]);
    }

    public function redirect(): array
    {
        return [
            'type' => 'path',
            'location' => '/order/success?booking_id='. $this->booking->id
        ];
    }

    public function message(): string
    {
        return __('messages.bookings.created');
    }
}

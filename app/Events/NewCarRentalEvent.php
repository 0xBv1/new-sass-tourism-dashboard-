<?php

namespace App\Events;

use App\Models\CarRental;
use Illuminate\Foundation\Events\Dispatchable;

class NewCarRentalEvent
{
    use Dispatchable;

    public CarRental $carRental;

    public function __construct(CarRental $carRental)
    {
        $this->carRental = $carRental;
    }
}

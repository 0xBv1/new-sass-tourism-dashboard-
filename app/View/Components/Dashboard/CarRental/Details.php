<?php

namespace App\View\Components\Dashboard\CarRental;

use App\Models\CarRental;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Details extends Component
{
    private CarRental $carRental;

    public function __construct(CarRental $carRental)
    {
        $this->carRental = $carRental;
    }

    public function render(): View
    {
        return view('components.dashboard.car-rental.details', [
            'carRental' => $this->carRental
        ]);
    }
}

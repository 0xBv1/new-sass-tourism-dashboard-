<?php

namespace App\View\Components\Dashboard\Booking;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToursList extends Component
{
    private Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function render(): View
    {
        return view('components.dashboard.booking.tours-list', [
            'booking' => $this->booking
        ]);
    }
}

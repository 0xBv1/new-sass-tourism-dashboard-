<?php

namespace App\Events;

use App\Models\CustomTrip;
use Illuminate\Foundation\Events\Dispatchable;

class NewCustomTripRequestEvent
{
    use Dispatchable;

    public CustomTrip $customTrip;

    public function __construct(CustomTrip $customTrip)
    {
        $this->customTrip = $customTrip;
    }
}

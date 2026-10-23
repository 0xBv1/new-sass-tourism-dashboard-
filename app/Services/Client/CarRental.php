<?php

namespace App\Services\Client;

use App\Exceptions\InvalidStopLocationException;
use App\Models\CarRoute;
use App\Models\CarRoutePrice;
use App\Models\Location;
use Throwable;

class CarRental
{

    public function availableDestinations($pickup)
    {
        $routes = CarRoute::select(['pickup_location_id', 'destination_id'])
            ->where('pickup_location_id', $pickup)
            ->orWhere('destination_id', $pickup)
            ->get();

        $locations = $routes->pluck('pickup_location_id')
            ->merge($routes->pluck('destination_id')->toArray())
            ->filter(fn($id) => $id != $pickup)
            ->unique()
            ->toArray();

        return Location::whereIn('id', $locations)->get();
    }

    public function search($pickup, $destination): ?CarRoute
    {
        return CarRoute::with(['pickup', 'destination', 'stops.location', 'prices'])
            ->where(fn($q) => $q->where('pickup_location_id', $pickup)
                ->where('destination_id', $destination)
            )->orWhere(fn($q) => $q->where('destination_id', $pickup)
                ->where('pickup_location_id', $destination)
            )->first();
    }

    /**
     * @throws Throwable
     */
    public function validateStops($stops, CarRoute $carRoute): bool
    {
        if (empty($stops)) {
            return true;
        }
        foreach ($stops as $stopId) {
            if (!$carRoute->stops->firstWhere('stop_location_id', $stopId)) {
                throw new InvalidStopLocationException(Location::find($stopId)?->name);
            }
        }
        return true;
    }


    public function searchForSuitableCar($adults, $children, CarRoute $carRoute): CarRoutePrice|\Closure|null
    {
        $members = $children + $adults;

        return $carRoute->prices->filter(fn($group) => $group['from'] <= $members && $members <= $group['to'])->first();
    }
}

<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;

class BookingRentalResource extends JsonResource
{
    protected array $relations = [
        'pickup' => ['type' => 'single', 'resourceClass'=> '\App\Http\Resources\Api\LocationResource'],
        'destination' => ['type' => 'single', 'resourceClass'=> '\App\Http\Resources\Api\LocationResource'],
        'stops' => ['type' => 'collection', 'resourceClass'=> '\App\Http\Resources\Api\BookingRentalStopResource'],
    ];
}

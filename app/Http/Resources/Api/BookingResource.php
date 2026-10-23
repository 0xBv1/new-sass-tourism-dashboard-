<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;

class BookingResource extends JsonResource
{
    protected array $relations = [
        'tours' => ['type' => 'collection', 'resourceClass'=> '\App\Http\Resources\Api\BookingTourResource'],
        'rentals' => ['type' => 'collection', 'resourceClass'=> '\App\Http\Resources\Api\BookingRentalResource'],
        'coupon' => ['type' => 'single', 'resourceClass'=> '\App\Http\Resources\Api\CouponResource'],
        'currency' => ['type' => 'single', 'resourceClass'=> '\App\Http\Resources\Api\CurrencyResource'],
    ];
}

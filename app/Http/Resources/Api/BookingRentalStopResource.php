<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;

class BookingRentalStopResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource?->location?->name,
            'price' => $this->resource?->price ?? 0
        ];
    }
}

<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;
use App\Models\CartItem;
use App\Models\CartRental;
use Carbon\Carbon;


/**
 * @property CartItem|CartRental $resource
 */
class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        $itemType = $this->resource->item_type;

        return [
            'type' => $itemType,
            'adults' => $this->resource->adults,
            'children' => $this->resource->children,
            ... $this->{$itemType}()
        ];
    }

    private function tour(): array
    {
        return [
            'tour' => new TourResource($this->resource->tour),
            'options' => TourOptionResource::collection($this->resource->options()),
            'infants' => $this->resource->infants,
            'start_date' => $this->resource->start_date,
        ];
    }

    private function rental(): array
    {
        return [
            'id' => $this->resource->id,
            'car_image' => "https://cdn2.rcstatic.com/images/car_images_b/web/toyota/corolla_lrg.jpg",
            'pickup' => new LocationResource($this->resource->pickup),
            'destination' => new LocationResource($this->resource->destination),
            'stops' => $this->resource->stops,
            'pickup_date' => $this->resource->pickup_date->toDateString(),
            'car_route_price' => $this->resource->car_route_price,
            'car_type' => $this->resource->car_type,
            'oneway' => $this->resource->oneway,
            'pickup_time' => $this->resource->pickup_time ? Carbon::parse($this->resource->pickup_time)
                ->format('H:i') : $this->resource->pickup_time,
        ];
    }
}

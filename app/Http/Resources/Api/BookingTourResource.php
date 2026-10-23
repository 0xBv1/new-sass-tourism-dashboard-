<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;
use App\Models\TourOption;

class BookingTourResource extends JsonResource
{
    public function toArray($request): array
    {
        $options = TourOption::withTrashed()
            ->whereIn('id', collect($this->resource->pivot->options)->pluck('id')->toArray())
            ->get();

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'featured_image' => $this->resource->featured_image,
            'gallery' => $this->resource->gallery,
            'adults' => $this->resource->pivot->adults,
            'children' => $this->resource->pivot->children,
            'infants' => $this->resource->pivot->infants,
            'adult_price' => $this->resource->pivot->adult_price,
            'child_price' => $this->resource->pivot->child_price,
            'infant_price' => $this->resource->pivot->infant_price,
            'start_date' => $this->resource->pivot->start_date,
            'options' => array_map(fn($option) => [
                'id' => $option['id'],
                'adult_price' => (float)$option['adult_price'],
                'child_price' => (float)$option['child_price'],
                'name' => $options->firstWhere('id', $option['id'])?->name,
                'description' => $options->firstWhere('id', $option['id'])?->description,
            ], $this->resource->pivot->options),
        ];
    }
}

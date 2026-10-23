<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TourSeasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'enabled' => $this->enabled,
            'pricing_groups' => $this->pricing_groups,
            'available' => $this->available ?? [],
            'accommodation' => $this->available['accommodation'] ?? [],
            'without_accommodation' => $this->available['without_accommodation'] ?? [],
            'calender_availability' => $this->calender_availability,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

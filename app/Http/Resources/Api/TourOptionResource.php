<?php

namespace App\Http\Resources\Api;



use App\Http\Resources\JsonResource;

class TourOptionResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = $this->resource->toArray();
        unset($data['pivot'], $data['translation'], $data['translations']);
        return $data;
    }
}

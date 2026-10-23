<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\TourResource;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\DestinationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slider' => $this->when(
                $this->resource['slider'],
                fn() => new SliderResource($this->resource['slider'])
            ),
            'team' => EmployeeResource::collection($this->resource['team'] ?? []),
            'featured_tours' => TourResource::collection($this->resource['featured_tours'] ?? []),
            'testimonials' => CommentResource::collection($this->resource['testimonials'] ?? []),
            'featured_categories' => CategoryResource::collection($this->resource['featured_categories'] ?? []),
            'featured_destinations' => DestinationResource::collection($this->resource['featured_destinations'] ?? []),
        ];
    }
}

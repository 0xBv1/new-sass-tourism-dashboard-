<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'hotel_id' => $this->hotel_id,
            'hotel' => $this->whenLoaded('hotel'),
            'featured_image' => $this->featured_image,
            'banner' => $this->banner,
            'gallery' => $this->gallery,
            'enabled' => $this->enabled,
            'bed_count' => $this->bed_count,
            'room_type' => $this->room_type,
            'max_capacity' => $this->max_capacity,
            'bed_types' => $this->bed_types,
            'night_price' => $this->night_price,
            'children' => $this->whenLoaded('children'),
            'adults' => $this->whenLoaded('adults'),
            'amenities' => $this->whenLoaded('amenities'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomAvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'hotel' => [
                'id' => $this->id,
                'name' => $this->name,
                'city' => $this->city,
                'slug' => $this->slug,
                'stars' => $this->stars,
                'description' => $this->description,
                'featured_image' => $this->featured_image,
                'banner' => $this->banner,
                'gallery' => $this->gallery,
                'address' => $this->address,
                'phone_contact' => $this->phone_contact,
                'whatsapp_contact' => $this->whatsapp_contact,
                'amenities' => $this->whenLoaded('amenities', function () {
                    return $this->amenities->map(function ($amenity) {
                        return [
                            'id' => $amenity->id,
                            'name' => $amenity->name,
                        ];
                    });
                }),
            ],
            'available_rooms' => $this->whenLoaded('availableRooms', function () {
                return $this->availableRooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'description' => $room->description,
                        'slug' => $room->slug,
                        'featured_image' => $room->featured_image,
                        'banner' => $room->banner,
                        'gallery' => $room->gallery,
                        'bed_count' => $room->bed_count,
                        'room_type' => $room->room_type,
                        'max_capacity' => $room->max_capacity,
                        'bed_types' => $room->bed_types,
                        'night_price' => $room->night_price,
                        'extra_bed_available' => $room->extra_bed_available,
                        'extra_bed_price' => $room->extra_bed_price,
                        'max_extra_beds' => $room->max_extra_beds,
                        'extra_bed_description' => $room->extra_bed_description,
                        'total_capacity' => $room->getTotalCapacity(),
                        'amenities' => $room->relationLoaded('amenities') ? $room->amenities->map(function ($amenity) {
                            return [
                                'id' => $amenity->id,
                                'name' => $amenity->name,
                            ];
                        }) : [],
                    ];
                });
            }),
            'total_available_rooms' => $this->when(isset($this->availableRooms), function () {
                return $this->availableRooms->count();
            }),
        ];
    }
}

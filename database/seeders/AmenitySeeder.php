<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            'Free Wi-Fi',
            'Swimming Pool',
            'Fitness Center',
            'Restaurant',
            'Room Service',
            'Spa Services',
            'Business Center',
            'Conference Rooms',
            'Parking',
            'Airport Shuttle',
            'Pet Friendly',
            'Laundry Service',
            'Concierge Service',
            '24-Hour Front Desk',
            'Air Conditioning',
            'Mini Bar',
            'Safe Deposit Box',
            'Complimentary Breakfast',
            'Bar/Lounge',
            'Babysitting Services'
        ];

        foreach ($amenities as $amenity) {
            if (!Amenity::whereTranslation('name', $amenity)->exists()) {
                Amenity::create($this->build($amenity));
            }
        }
    }

    private function build($amenity): array
    {
        $attributes = [
            'name' => Str::headline($amenity),
        ];
        $data = [];

        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }

        return $data;
    }
}

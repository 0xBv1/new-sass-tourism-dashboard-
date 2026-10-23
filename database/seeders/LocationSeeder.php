<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Cairo',
            'Giza',
            'Cairo Int. Airport (CAI',
            '6th of October City',
            'New Administrative Capital',
            'Port Said',
            'Suez',
            'Ain Sokhna',
            'El Shokna Port',
            'Alexandria City',
            'Alexandria Port',
            'Borg El Arab Airport',
            'Alex. International Airport',
            'Al Alamein',
            'Siwa',
            'El Menia',
            'Luxor',
            'Hurghada',
            'Sharm El Sheikh',
            'Saint Catherine',
            'Taba',
            'El Fayoum',
            'El Fayoum (Tunis Village)',
            '4 Hours car rental inside Cairo',
            '8 Hours car rental inside Cairo',
        ];

        foreach ($locations as $location) {
            if (!Location::whereTranslation('name', $location)->exists()) {
                $loc = [];
                foreach (config('translatable.locales') as $locale) {
                    $loc[$locale]['name'] = $location;
                }
                Location::create($loc);
            }
        }
    }
}

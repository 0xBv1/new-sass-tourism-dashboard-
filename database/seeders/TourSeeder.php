<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Duration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tours = [
            [
                'title' => 'Cairo Pyramids Day Tour',
                'overview' => 'Explore the ancient wonders of Egypt with our comprehensive Cairo Pyramids tour.',
                'slug' => 'cairo-pyramids-day-tour',
                'duration_id' => 1, // 1 Day
                'adult_price' => 150.00,
                'child_price' => 100.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 1,
                'code' => 'TOUR001',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Luxor Valley of Kings Adventure',
                'overview' => 'Discover the tombs of ancient pharaohs in the Valley of the Kings.',
                'slug' => 'luxor-valley-of-kings-adventure',
                'duration_id' => 3, // 2 Days 1 Night
                'adult_price' => 280.00,
                'child_price' => 200.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 2,
                'code' => 'TOUR002',
                'duration_in_days' => 2,
            ],
            [
                'title' => 'Aswan Nile Cruise Experience',
                'overview' => 'Cruise along the majestic Nile River and visit historic temples.',
                'slug' => 'aswan-nile-cruise-experience',
                'duration_id' => 4, // 3 Days 2 Nights
                'adult_price' => 450.00,
                'child_price' => 350.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 3,
                'code' => 'TOUR003',
                'duration_in_days' => 3,
            ],
            [
                'title' => 'Egypt Complete Heritage Tour',
                'overview' => 'Comprehensive tour covering all major historical sites in Egypt.',
                'slug' => 'egypt-complete-heritage-tour',
                'duration_id' => 5, // 5 Days 4 Nights
                'adult_price' => 750.00,
                'child_price' => 600.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 4,
                'code' => 'TOUR004',
                'duration_in_days' => 5,
            ],
            [
                'title' => 'Alexandria Coastal Discovery',
                'overview' => 'Explore the Mediterranean coast and ancient Alexandria.',
                'slug' => 'alexandria-coastal-discovery',
                'duration_id' => 6, // 7 Days 6 Nights
                'adult_price' => 920.00,
                'child_price' => 750.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 5,
                'code' => 'TOUR005',
                'duration_in_days' => 7,
            ],
            [
                'title' => 'Sinai Desert Safari',
                'overview' => 'Adventure through the stunning Sinai desert landscape.',
                'slug' => 'sinai-desert-safari',
                'duration_id' => 7, // 10 Days 9 Nights
                'adult_price' => 1200.00,
                'child_price' => 1000.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 6,
                'code' => 'TOUR006',
                'duration_in_days' => 10,
            ],
            [
                'title' => 'Cairo Half-Day City Tour',
                'overview' => 'Quick tour of Cairo\'s main attractions in just a few hours.',
                'slug' => 'cairo-half-day-city-tour',
                'duration_id' => 9, // 2 Hours
                'adult_price' => 80.00,
                'child_price' => 60.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 7,
                'code' => 'TOUR007',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Giza Full-Day Exploration',
                'overview' => 'Spend a full day exploring the Giza plateau and pyramids.',
                'slug' => 'giza-full-day-exploration',
                'duration_id' => 10, // 4 Hours
                'adult_price' => 120.00,
                'child_price' => 90.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 8,
                'code' => 'TOUR008',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Disabled Tour Example',
                'overview' => 'This tour is disabled and should not be counted.',
                'slug' => 'disabled-tour-example',
                'duration_id' => 1, // 1 Day
                'adult_price' => 100.00,
                'child_price' => 80.00,
                'infant_price' => 0.00,
                'enabled' => false,
                'featured' => false,
                'display_order' => 9,
                'code' => 'TOUR009',
                'duration_in_days' => 1,
            ],
            // Additional tours for better testing
            [
                'title' => 'Cairo Museum & Old City Tour',
                'overview' => 'Visit the Egyptian Museum and explore the historic old city of Cairo.',
                'slug' => 'cairo-museum-old-city-tour',
                'duration_id' => 1, // 1 Day
                'adult_price' => 120.00,
                'child_price' => 80.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 10,
                'code' => 'TOUR010',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Islamic Cairo Walking Tour',
                'overview' => 'Explore the beautiful Islamic architecture and mosques of Cairo.',
                'slug' => 'islamic-cairo-walking-tour',
                'duration_id' => 1, // 1 Day
                'adult_price' => 90.00,
                'child_price' => 60.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 11,
                'code' => 'TOUR011',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Luxor Temple & Karnak Complex',
                'overview' => 'Visit the magnificent Luxor Temple and the vast Karnak temple complex.',
                'slug' => 'luxor-temple-karnak-complex',
                'duration_id' => 3, // 2 Days 1 Night
                'adult_price' => 320.00,
                'child_price' => 240.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 12,
                'code' => 'TOUR012',
                'duration_in_days' => 2,
            ],
            [
                'title' => 'Abu Simbel Temple Tour',
                'overview' => 'Journey to the magnificent Abu Simbel temples built by Ramses II.',
                'slug' => 'abu-simbel-temple-tour',
                'duration_id' => 4, // 3 Days 2 Nights
                'adult_price' => 380.00,
                'child_price' => 280.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 13,
                'code' => 'TOUR013',
                'duration_in_days' => 3,
            ],
            [
                'title' => 'Red Sea Diving Adventure',
                'overview' => 'Explore the underwater wonders of the Red Sea with professional diving.',
                'slug' => 'red-sea-diving-adventure',
                'duration_id' => 5, // 5 Days 4 Nights
                'adult_price' => 850.00,
                'child_price' => 650.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 14,
                'code' => 'TOUR014',
                'duration_in_days' => 5,
            ],
            [
                'title' => 'Western Desert Oasis Tour',
                'overview' => 'Discover the hidden oases and ancient trade routes of the Western Desert.',
                'slug' => 'western-desert-oasis-tour',
                'duration_id' => 6, // 7 Days 6 Nights
                'adult_price' => 1100.00,
                'child_price' => 900.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 15,
                'code' => 'TOUR015',
                'duration_in_days' => 7,
            ],
            [
                'title' => 'Egypt & Jordan Combined Tour',
                'overview' => 'Comprehensive tour covering both Egypt and Jordan\'s historical sites.',
                'slug' => 'egypt-jordan-combined-tour',
                'duration_id' => 7, // 10 Days 9 Nights
                'adult_price' => 1500.00,
                'child_price' => 1200.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 16,
                'code' => 'TOUR016',
                'duration_in_days' => 10,
            ],
            [
                'title' => 'Cairo Food & Culture Tour',
                'overview' => 'Experience authentic Egyptian cuisine and local culture.',
                'slug' => 'cairo-food-culture-tour',
                'duration_id' => 9, // 2 Hours
                'adult_price' => 60.00,
                'child_price' => 40.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 17,
                'code' => 'TOUR017',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Nile Dinner Cruise',
                'overview' => 'Enjoy a romantic dinner cruise on the Nile with live entertainment.',
                'slug' => 'nile-dinner-cruise',
                'duration_id' => 10, // 4 Hours
                'adult_price' => 150.00,
                'child_price' => 100.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => true,
                'display_order' => 18,
                'code' => 'TOUR018',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Cairo Night Photography Tour',
                'overview' => 'Capture stunning night photos of Cairo\'s illuminated landmarks.',
                'slug' => 'cairo-night-photography-tour',
                'duration_id' => 11, // 8 Hours
                'adult_price' => 200.00,
                'child_price' => 150.00,
                'infant_price' => 0.00,
                'enabled' => true,
                'featured' => false,
                'display_order' => 19,
                'code' => 'TOUR019',
                'duration_in_days' => 1,
            ],
            [
                'title' => 'Another Disabled Tour',
                'overview' => 'Another disabled tour that should not be counted.',
                'slug' => 'another-disabled-tour',
                'duration_id' => 5, // 5 Days 4 Nights
                'adult_price' => 500.00,
                'child_price' => 400.00,
                'infant_price' => 0.00,
                'enabled' => false,
                'featured' => false,
                'display_order' => 20,
                'code' => 'TOUR020',
                'duration_in_days' => 5,
            ],
        ];

        foreach ($tours as $tourData) {
            $durationId = $tourData['duration_id'];
            unset($tourData['duration_id']);

            $tour = Tour::create($tourData);
            
            // Associate tour with duration
            $duration = Duration::find($durationId);
            if ($duration) {
                $duration->tours()->attach($tour->id);
            }
        }

        // Update tours_count for all durations
        Duration::all()->each(function ($duration) {
            $duration->setToursCount();
        });
    }
}

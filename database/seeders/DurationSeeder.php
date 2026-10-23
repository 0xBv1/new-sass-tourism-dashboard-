<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Duration;
use Illuminate\Support\Str;

class DurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $durations = [
            [
                'title' => '1 Day',
                'description' => 'Perfect for a quick day trip or experience',
                'days' => 1,
                'nights' => 0,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => false,
                'display_order' => 1
            ],
            [
                'title' => '2 Days 1 Night',
                'description' => 'Great for a weekend getaway',
                'days' => 2,
                'nights' => 1,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => true,
                'display_order' => 2
            ],
            [
                'title' => '3 Days 2 Nights',
                'description' => 'Perfect for a short vacation',
                'days' => 3,
                'nights' => 2,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => true,
                'display_order' => 3
            ],
            [
                'title' => '5 Days 4 Nights',
                'description' => 'Ideal for exploring multiple destinations',
                'days' => 5,
                'nights' => 4,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => true,
                'display_order' => 4
            ],
            [
                'title' => '7 Days 6 Nights',
                'description' => 'A full week of adventure',
                'days' => 7,
                'nights' => 6,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => false,
                'display_order' => 5
            ],
            [
                'title' => '10 Days 9 Nights',
                'description' => 'Extended vacation package',
                'days' => 10,
                'nights' => 9,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => false,
                'display_order' => 6
            ],
            [
                'title' => '14 Days 13 Nights',
                'description' => 'Two weeks of incredible experiences',
                'days' => 14,
                'nights' => 13,
                'duration_type' => 'days',
                'enabled' => true,
                'featured' => false,
                'display_order' => 7
            ],
            [
                'title' => '2 Hours',
                'description' => 'Quick tour or activity',
                'days' => 2,
                'nights' => null,
                'duration_type' => 'hours',
                'enabled' => true,
                'featured' => false,
                'display_order' => 8
            ],
            [
                'title' => '4 Hours',
                'description' => 'Half-day experience',
                'days' => 4,
                'nights' => null,
                'duration_type' => 'hours',
                'enabled' => true,
                'featured' => false,
                'display_order' => 9
            ],
            [
                'title' => '8 Hours',
                'description' => 'Full-day experience',
                'days' => 8,
                'nights' => null,
                'duration_type' => 'hours',
                'enabled' => true,
                'featured' => false,
                'display_order' => 10
            ]
        ];

        foreach ($durations as $durationData) {
            $duration = Duration::create([
                'slug' => Str::slug($durationData['title']),
                'enabled' => $durationData['enabled'],
                'featured' => $durationData['featured'],
                'display_order' => $durationData['display_order'],
                'days' => $durationData['days'],
                'nights' => $durationData['nights'],
                'duration_type' => $durationData['duration_type'],
            ]);

            // Add translations
            $duration->translateOrNew('en')->title = $durationData['title'];
            $duration->translateOrNew('en')->description = $durationData['description'];
            $duration->save();

            echo "Created duration: {$durationData['title']}\n";
        }
    }
} 
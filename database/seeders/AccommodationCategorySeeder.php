<?php

namespace Database\Seeders;

use App\Models\AccommodationCategory;
use Illuminate\Database\Seeder;

class AccommodationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'stander',
                'name' => 'Standard',
                'display_order' => 1,
            ],
            [
                'slug' => 'gold',
                'name' => 'Gold',
                'display_order' => 2,
            ],
            [
                'slug' => 'luxury',
                'name' => 'Luxury',
                'display_order' => 3,
            ],
            [
                'slug' => 'platinum',
                'name' => 'Platinum',
                'display_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            $translations = [];
            foreach (config('translatable.locales') as $locale) {
                $translations[$locale] = ['name' => $category['name']];
            }

            AccommodationCategory::firstOrCreate(
                ['slug' => $category['slug']],
                array_merge([
                    'display_order' => $category['display_order'],
                    'enabled' => true,
                ], $translations)
            );
        }
    }
}

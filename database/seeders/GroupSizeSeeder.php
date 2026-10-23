<?php

namespace Database\Seeders;

use App\Models\GroupSize;
use Illuminate\Database\Seeder;

class GroupSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            [
                'slug' => 'solo',
                'name' => 'Solo',
                'min_people' => 1,
                'max_people' => 1,
                'display_order' => 1,
            ],
            [
                'slug' => '2-4',
                'name' => '2-4 People',
                'min_people' => 2,
                'max_people' => 4,
                'display_order' => 2,
            ],
            [
                'slug' => '5-8',
                'name' => '5-8 People',
                'min_people' => 5,
                'max_people' => 8,
                'display_order' => 3,
            ],
            [
                'slug' => '9-16',
                'name' => '9-16 People',
                'min_people' => 9,
                'max_people' => 16,
                'display_order' => 4,
            ],
        ];

        foreach ($sizes as $size) {
            $translations = [];
            foreach (config('translatable.locales') as $locale) {
                $translations[$locale] = ['name' => $size['name']];
            }

            GroupSize::firstOrCreate(
                ['slug' => $size['slug']],
                array_merge([
                    'min_people' => $size['min_people'],
                    'max_people' => $size['max_people'],
                    'display_order' => $size['display_order'],
                    'enabled' => true,
                ], $translations)
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            'Egypt' => [
                'Cairo',
                'Alexandria',
                'Fayoum',
                'Luxor',
                'Aswan',
                'Sharm El-Sheikh',
                'Hurghada',
                'Deserts',
                'Dahab',
                'Taba',
            ],
            'Jordan' => [],
            'Jerusalem' => [],
            'Morocco' => [],
            'South Africa' => [],
            'India' => [],
            'England' => [],
            'New York' => [],
        ];

        foreach ($destinations as $parent => $sub) {
            if (!Destination::whereTranslation('title', $parent)->exists()) {
                $parent_destination = Destination::create($this->build($parent));
                $parent_destination->seo()->create($this->seo($parent));
                foreach ($sub as $subDestination) {
                    if (!Destination::whereTranslation('title', $subDestination)->exists()) {
                        $subCat = $parent_destination->children()->create($this->build($subDestination));
                        $subCat->seo()->create($this->seo($subDestination));
                    }
                }
            }
        }
    }

    private function build($destination): array
    {
        $attributes = [
            'title' => Str::headline($destination),
            'description' => Str::headline($destination),
        ];
        $data = [];
        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }
        return array_merge($data, ['slug' => Str::slug($destination)]);
    }

    private function seo($destination): array
    {
        $attributes = [
            'og_title' => Str::headline($destination),
            'meta_title' => Str::headline($destination),
        ];
        $data = [];
        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }
        return $data;
    }
}

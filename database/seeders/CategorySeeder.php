<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Multi days tours' => [
                'Adventure Tours',
                'Culture Tours',
                'Cairo City Breaks',
                'Christmas & NY Offers',
                'Egypt Classic Tours',
                'Egypt Small Group Tours',
                'Egypt Spiritual Tours',
                'Honeymoon',
                'Wheelchair Accessible',
            ],


            'Day Tours' => [
                'night tours',
                'half day tour',
                'Unusual Tours',
            ],


            'Nile Cruises' => [
                'Aswan To Luxor Cruise',
                'Luxor To Aswan Cruise',
                'Lake Nasser Cruise',
                'Dahabiyat'
            ]
        ];
        foreach ($categories as $parent => $sub) {
            if (!Category::whereTranslation('title', $parent)->exists()) {
                $parent_category = Category::create($this->build($parent));
                $parent_category->seo()->create($this->seo($parent));
                foreach ($sub as $subCategory) {
                    if (!Category::whereTranslation('title', $subCategory)->exists()) {
                        $subCat = $parent_category->children()->create($this->build($subCategory));
                        $subCat->seo()->create($this->seo($subCategory));
                    }
                }
            }
        }
    }

    private function build($category): array
    {
        $attributes = [
            'title' => Str::headline($category),
            'description' => Str::headline($category),
        ];
        $data = [];
        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }
        return array_merge(['slug' => Str::slug($category) ], $data);
    }

    private function seo($category): array
    {
        $attributes = [
            'og_title' => Str::headline($category),
            'meta_title' => Str::headline($category),
        ];
        $data = [];
        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }
        return $data;
    }
}

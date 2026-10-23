<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Destination Guides',
            'Adventure Travel',
            'Travel Tips and Advice',
            'Food and Culinary Experiences',
            'Family Friendly Travel',
            'Solo Travel',
            'Cultural Experiences',
            'Sustainable and Eco Tourism',
            'Luxury Travel',
            'Off the Beaten Path Travel',
        ];

        foreach ($categories as $category) {
            $category = trim($category);
            if (!BlogCategory::whereTranslation('title', $category)->exists()) {
                $blog_category = BlogCategory::create($this->build($category));
                $blog_category->autoTranslate();
            }
        }
    }

    private function build($category): array
    {
        $attributes = [
            'title' => Str::headline($category),
        ];
        $data = [];
        foreach (config('translatable.locales') as $locale) {
            $data[$locale] = $attributes;
        }
        return array_merge(['slug' => Str::slug($category)], $data);
    }
}

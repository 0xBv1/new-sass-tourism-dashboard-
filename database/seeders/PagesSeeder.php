<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        array_map(function($page) {
            $titles = [];
            foreach (config('translatable.locales') as $loc) {
                $titles[$loc] = [
                    'title' => str($page)->headline()
                ];
            }
            $page = Page::firstOrCreate([
                'key' => $page
            ], array_merge($titles, [
                'key' => $page
            ]));
            $page->load('seo');
            if (!$page->seo) {
                $seo = [
                    'meta_title' => null,
                    'meta_description' => null,
                    'meta_keywords' => null,
                    'og_title' => null,
                    'og_description' => null,
                ];
                $data = [];
                foreach (config('translatable.locales') as $local) {
                    $data[$local] = $seo;
                }
                $page->seo()->create($data);
            }
        }, Page::MAIN_PAGES);
    }
}

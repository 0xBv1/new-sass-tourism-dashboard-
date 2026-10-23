<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourOption;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ToursFetchCommand extends Command
{
    protected $signature = 'tours:fetch';

    protected $description = 'Command description';

    public function handle(): void
    {
        $http = Http::baseUrl('https://justflytravels.com')
            ->withHeaders(['Accept' => 'application/json']);
        $page = 1;
        $response = $http->get('api/tours', [
            'page' => $page,
            'includes' => 'translations,categories.translations,days.translations,destinations.translations,options.translations',
        ]);
        if ($response->collect('data.data')->isEmpty()) {
            $this->info('No data found.');
        }

        $this->output->progressStart($response->json('data.total') ?? 0);

        while (true) {
            if ($response->failed()) {
                $this->error('Error: ' . $response->body());
                return;
            }

            $tours = $response->json('data.data');

            if (empty($tours)) {
                break;
            }

            foreach ($tours as $tour) {
                $this->saveTour($tour);
                $this->output->progressAdvance();
            }
            $page++;
            $response = $http->get('api/tours', [
                'page' => $page,
                'includes' => 'translations,categories.translations,days.translations,destinations.translations,options.translations',
            ]);
        }
        $this->output->progressFinish();
    }

    private function saveTour(mixed $tour): void
    {
        $t = Tour::where('slug', $tour['slug'])->firstOrNew();

        $tour_payload = [
            'enabled' => true,
            'slug' => $tour['slug'],
            'display_order' => $tour['display_order'] ?? 0,
            'code' => $tour['code'] ?? null,
            'featured' => $tour['featured'] ?? false,
            'featured_image' => $this->saveImage('tours/' . $tour['slug'], $tour['featured_image']),
            'gallery' => empty($tour['gallery']) ? [] : array_map(fn($i) => $this->saveImage('tours/' . $tour['slug'], $i), $tour['gallery']),
            'adult_price' => $tour['adult_price'] ?? 0,
            'child_price' => $tour['child_price'] ?? 0,
            'infant_price' => $tour['infant_price'] ?? 0,
            'pricing_groups' => $tour['pricing_groups'] ?? null,
            'duration_in_days' => $tour['duration_in_days'] ?? 1,
            'available' => null,
            'en' => [
                'title' => $tour['title'] ?? null,
                'overview' => $tour['overview'] ?? null,
                'highlights' => $tour['highlights'] ?? null,
                'excluded' => $tour['excluded'] ?? null,
                'included' => $tour['included'] ?? null,
                'duration' => $tour['duration'] ?? null,
                'type' => $tour['type'] ?? null,
                'run' => $tour['run'] ?? null,
                'pickup_time' => $tour['pickup_time'] ?? null,
            ]
        ];

        foreach (config('translatable.locales') as $locale) {
            if ($locale == config('app.locale')) {
                continue;
            }
            $translation = collect($tour['translations'])->where('locale', $locale)->first();
            $tour_payload[$locale] = $translation ?? $tour_payload['en'];
        }
        //handle destinations
        $destinations = [];
        foreach ($tour['destinations'] as $destination) {
            $dest_payload = [
                'enabled' => true,
                'slug' => $destination['slug'],
                'featured' => $destination['featured'] ?? false,
                'banner' => $this->saveImage('destinations/' . $destination['slug'], $destination['banner'] ?? null),
                'featured_image' => $this->saveImage('destinations/' . $destination['slug'], $destination['featured_image'] ?? null),
                'gallery' => empty($destination['gallery']) ? [] : array_map(fn($i) => $this->saveImage('destinations/' . $destination['slug'], $i), $destination['gallery']),
                'display_order' => $destination['display_order'] ?? 0,
                'en' => [
                    'title' => $destination['title'] ?? null,
                    'description' => $destination['description'] ?? null,
                ]
            ];
            foreach (config('translatable.locales') as $locale) {
                if ($locale == config('app.locale')) {
                    continue;
                }
                $dest_payload[$locale] = collect($destination['translations'])->where('locale', $locale)->first() ?? $dest_payload['en'];
            }
            $destinations[] = Destination::updateOrCreate(['slug' => $destination['slug']], $dest_payload)->id;
        }

        //handle categories
        $categories = [];
        foreach ($tour['categories'] as $category) {
            $category_payload = [
                'enabled' => true,
                'slug' => $category['slug'],
                'featured' => $category['featured'] ?? false,
                'banner' => $this->saveImage('destinations/' . $category['slug'], $category['banner'] ?? null),
                'featured_image' => $this->saveImage('categories/' . $category['slug'], $category['featured_image'] ?? null),
                'gallery' => empty($destination['gallery']) ? [] : array_map(fn($i) => $this->saveImage('categories/' . $category['slug'], $i), $category['gallery']),
                'display_order' => $category['display_order'] ?? 0,
                'en' => [
                    'title' => $category['title'] ?? null,
                    'description' => $category['description'] ?? null,
                ]
            ];
            foreach (config('translatable.locales') as $locale) {
                if ($locale == config('app.locale')) {
                    continue;
                }
                $category_payload[$locale] = collect($category['translations'])->where('locale', $locale)->first() ?? $category_payload['en'];
            }
            $categories[] = Category::updateOrCreate(['slug' => $category['slug']], $category_payload)->id;
        }

        //handle options
        $options = [];
        foreach ($tour['options'] as $option) {
            $option_db = TourOption::whereTranslation('name', $option['name'])->first();
            if (!$option_db) {
                $option_payload = [
                    'adult_price' => $option['price'] ?? $option['adult_price'] ?? 0,
                    'child_price' => $option['child_price'] ?? 0,
                    'pricing_groups' => $option['pricing_groups'] ?? null,
                    'en' => ['name' => $option['name'], 'description' => $option['description']]
                ];
                foreach (config('translatable.locales') as $locale) {
                    if ($locale == config('app.locale')) {
                        continue;
                    }
                    $option_payload[$locale] = collect($option['translations'])->where('locale', $locale)->first() ?? $option_payload['en'];
                }
                $option_db = TourOption::create($option_payload);
            }
            $options[] = $option_db->id;
        }

        $t->fill($tour_payload)->save();
        $t->destinations()->sync($destinations);
        $t->categories()->sync($categories);
        $t->options()->sync($options);

        //handle days
        $t->days()->delete();
        foreach ($tour['days'] as $day) {
            $day_payload = [
                'en' => [
                    'title' => $day['title'] ?? null,
                    'description' => $day['description'] ?? null,
                ]
            ];
            foreach (config('translatable.locales') as $locale) {
                if ($locale == config('app.locale')) {
                    continue;
                }
                $day_payload[$locale] = collect($day['translations'])->where('locale', $locale)->first() ?? $day_payload['en'];
            }
            $t->days()->create($day_payload);
        }
    }

    private function saveImage($slug, ?string $imgLink): ?string
    {
        try {
            if (is_null($imgLink)) {
                return null;
            }

            $name = File::name($imgLink) . '.' . File::extension($imgLink);

            $path = storage_path('app/public/media/' . $slug);

            if (File::exists($path . '/' . $name)) {
                return asset("storage/media/$slug/$name");
            }

            File::ensureDirectoryExists($path);

            $req = Http::get($imgLink);

            throw_if($req->failed(), new \Exception('Error: ' . $req->status() . '->' . $req->body()));

            File::put("$path/$name", $req->body());

            return asset("storage/media/$slug/$name");
        } catch (\Exception|\Throwable $e) {
            info($slug . '=>' . $imgLink . '=>' . $e->getMessage());
            return null;
        }
    }
}

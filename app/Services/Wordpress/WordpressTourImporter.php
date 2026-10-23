<?php

namespace App\Services\Wordpress;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourOption;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WordpressTourImporter
{
    private $tour;

    public static $saved = 0;

    public function __construct($tour)
    {
        $this->tour = $tour;
        $this->save();
    }

    public static function seed($tour): WordpressTourImporter
    {
        return new self($tour);
    }

    private function save(): void
    {
        $title = Str::of($this->tour['post_title'])->trim()->headline();
        $seo = $day = [];
        $data = [
            'enabled' => $this->tour['post_status'] == 'publish',
            'featured' => $this->tour['metas']['_tour_featured'],
            'featured_image' => $this->featuredImage(),
            'gallery' => $this->gallery(),
            'adult_price' => $this->tour['metas']['_tour_price'],
            'child_price' => $this->tour['metas']['_tour_price_child'],
            'pricing_groups' => $this->tour['groups'],
        ];

        foreach (config('translatable.locales') as $local) {
            $data[$local] = [
                'title' => $title,
                'slug' => $this->tour['post_name'],
                'overview' => $this->tour['post_content'],
            ];
            $seo[$local] = [
                'og_title' => Str::headline($title),
                'meta_title' => Str::headline($title),
            ];
            $day[$local] = [
                'title' => null,
                'description' => null,
            ];
        }

        if (!Tour::whereTranslation('title', $title)->exists()) {
            $db_tour = Tour::create($data);
            self::$saved++;
            $db_tour->days()->create($day);
            $db_tour->seo()->create(array_merge([
                'og_image' => $data['featured_image']
            ],$seo));
            $db_tour->categories()->sync(Category::inRandomOrder()->limit(random_int(1, 5))->pluck('id')->toArray());
            $db_tour->destinations()->sync(Destination::inRandomOrder()->limit(random_int(1, 5))->pluck('id')->toArray());
            $db_tour->options()->sync(TourOption::inRandomOrder()->limit(random_int(1, 5))->pluck('id')->toArray());
        }

    }

    private function saveRemoteFile($link): ?string
    {

        try {
            if (!Http::get($link)->ok()) {
                \Log::info('Failed Load: ' . $link);
                return null;
            }

            $title = Str::of($this->tour['post_title'])->trim()->slug();

            $info = pathinfo($link);
            $contents = file_get_contents($link);
            $file = storage_path('app/tmp/' . $info['basename']);

            \File::ensureDirectoryExists(storage_path('app/tmp/'));

            if (!$contents) {
                return null;
            }

            file_put_contents($file, $contents);

            $uploaded_file = new UploadedFile($file, $info['basename']);

            $path = Storage::disk('media')->put('tours/' . $title, $uploaded_file);

            return asset('storage/media/' . $path);
        } catch (\Throwable $throwable){
            return null;
        }

    }

    private function featuredImage(): ?string
    {
        if (isset($this->tour['featured_image'])) {
            return $this->saveRemoteFile($this->tour['featured_image']);
        }
        return null;
    }

    private function gallery(): array
    {
        if ($this->tour['slider']) {
            return collect($this->tour['slider'])
                ->map(fn($link) => $this->saveRemoteFile($link))
                ->filter(fn($img) => !empty($img))
                ->toArray();
        }
        return [];
    }
}

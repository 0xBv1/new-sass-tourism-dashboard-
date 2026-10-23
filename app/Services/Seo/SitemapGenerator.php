<?php

namespace App\Services\Seo;

use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator
{
    private Sitemap $sitemap;
    private array $locales;

    public function __construct()
    {
        $this->sitemap = Sitemap::create();
        $this->locales = array_filter(config('translatable.locales'), fn($locale) => $locale != config('app.locale'));
    }

    private function pages(): self
    {
        $routes = [
            '',
            'about',
            'egypt-tours/one-day-tours',
            'egypt-tours/multi-days-tours',
            'egypt-tours/nile-cruises',
            'egypt-tours/shore-excursions',
            'hidden-gems',
            'make-your-trip',
            'rent-car',
            'contact',
            'global-tours',
            'blog',
            'privacy-and-cookies',
            'terms-and-conditions',
            'sun-pyramids-reward-program',
            'responsible-travel-policy',
            'faq',
        ];
        foreach ($routes as $route) {
            $this->sitemap->add(URL::create(site_url($route)));
            foreach ($this->locales as $localCode) {
                $this->sitemap->add(URL::create(site_url($localCode . '/' . $route)));
            }
        }
        return $this;
    }

    public function tours(): self
    {
        $tours = DB::table('tours')
            ->selectRaw('distinct(slug)')
            ->whereNotNull('slug')
            ->whereNull('deleted_at')
            ->where('enabled', true)
            ->get()->map(fn($tour) => $tour->slug);
        foreach ($tours as $tour) {
            $this->sitemap->add(URL::create(site_url("tour/$tour")));
            foreach ($this->locales as $localCode) {
                $this->sitemap->add(URL::create(site_url("$localCode/tour/$tour")));
            }
        }
        return $this;
    }

    public function blogs(): self
    {
        $blogs = DB::table('blogs')
            ->selectRaw('distinct(slug)')
            ->whereNotNull('slug')
            ->whereNull('deleted_at')
            ->where('active', true)
            ->get()
            ->map(fn($blog) => $blog->slug);

        foreach ($blogs as $blog) {
            $this->sitemap->add(URL::create(site_url("blog/$blog")));
            foreach ($this->locales as $localCode) {
                $this->sitemap->add(URL::create(site_url("$localCode/blog/$blog")));
            }
        }

        return $this;
    }

    public function blogCategories(): self
    {
        $parents = BlogCategory::select(['id', 'slug'])
            ->with('children')
            ->where('active', true)
            ->whereNull('parent_id')
            ->get();
        $routes = [];

        foreach ($parents as $category) {
            $routes[] = $category->slug;
            foreach ($category->children->where('active', true) as $child) {
                $routes[] = $category->slug . '/' . $child->slug;
            }
        }

        foreach ($routes as $route) {
            $this->sitemap->add(URL::create(site_url("blog/$route")));
            foreach ($this->locales as $localCode) {
                $this->sitemap->add(URL::create(site_url("$localCode/blog/$route")));
            }
        }

        return $this;
    }

    public function destinations(): self
    {
        $destinations = DB::table('destinations')
            ->selectRaw('distinct(slug)')
            ->whereNotNull('slug')
            ->whereNull('deleted_at')
            ->where('enabled', true)
            ->whereIn('parent_id', fn($query) => $query->select('id')->from('destinations')->where('slug', 'egypt'))
            ->get()
            ->map(fn($destination) => $destination->slug);

        foreach ($destinations as $destination) {
            $this->sitemap->add(URL::create(site_url("egypt-tours/one-day-tours/$destination/tours")));
            foreach ($this->locales as $localCode) {
                $this->sitemap->add(URL::create(site_url("$localCode/egypt-tours/one-day-tours/$destination/tours")));
            }
        }

        return $this;
    }

    public function toFile($file = 'sitemap.xml'): string
    {
        $path = storage_path('app/public/seo/');
        File::ensureDirectoryExists($path);
        $this->sitemap->writeToFile($path . $file);
        return $path . $file;
    }

    public static function run(): string
    {
        $instance = new self;
        return $instance
            ->pages()
            ->tours()
            ->blogs()
            ->destinations()
            ->blogCategories()
            ->toFile();
    }
}

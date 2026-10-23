<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class TranslateBlogCategoriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private array $ids;

    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    public function handle(): void
    {
        $query = BlogCategory::query();

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->chunk(5, function (Collection $blog_categories) {
            $blog_categories->each(fn(BlogCategory $blog_category) => $blog_category->autoTranslate());
            $blog_categories->each(fn(BlogCategory $blog_category) => $blog_category->seo ? $blog_category->seo->autoTranslate() : null);
        });
    }
}

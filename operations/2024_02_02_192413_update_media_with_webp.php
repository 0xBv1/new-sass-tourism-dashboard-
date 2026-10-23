<?php

use App\Models\Tour;
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Category;
use App\Models\BlogCategory;

use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation {
    /**
     * Determine if the operation is being processed asyncronously.
     */
    protected bool $async = false;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        $this->tours();
        $this->blogs();
        $this->categories();
        $this->destinations();
        $this->blogCategories();
    }

    private function tours(): void
    {
        Tour::withTrashed()->chunkById(100, function ($objects) {
            $objects->each(function (Tour $object) {
                if ($object->featured_image) {
                    $extension = str($object->featured_image)->explode('.')->last();
                    $object->featured_image = str($object->featured_image)->replace('.' . $extension, '.webp')->toString();
                }
                if (!empty($object->gallery)) {
                    $object->gallery = array_map(function ($img) {
                        $extension = str($img)->explode('.')->last();
                        return str($img)->replace('.' . $extension, '.webp')->toString();
                    }, $object->gallery);
                }
                $object->save();
            });
        });
    }

    private function blogs(): void
    {
        Blog::withTrashed()->chunkById(100, function ($objects) {
            $objects->each(function (Blog $object) {
                if ($object->featured_image) {
                    $extension = str($object->featured_image)->explode('.')->last();
                    $object->featured_image = str($object->featured_image)->replace('.' . $extension, '.webp')->toString();
                }
                if (!empty($object->gallery)) {
                    $object->gallery = array_map(function ($img) {
                        $extension = str($img)->explode('.')->last();
                        return str($img)->replace('.' . $extension, '.webp')->toString();
                    }, $object->gallery);
                }
                $object->save();
            });
        });
    }

    private function categories(): void
    {
        Category::withTrashed()->chunkById(100, function ($objects) {
            $objects->each(function (Category $object) {
                if ($object->featured_image) {
                    $extension = str($object->featured_image)->explode('.')->last();
                    $object->featured_image = str($object->featured_image)->replace('.' . $extension, '.webp')->toString();
                }
                if ($object->banner) {
                    $extension = str($object->banner)->explode('.')->last();
                    $object->banner = str($object->banner)->replace('.' . $extension, '.webp')->toString();
                }
                if (!empty($object->gallery)) {
                    $object->gallery = array_map(function ($img) {
                        $extension = str($img)->explode('.')->last();
                        return str($img)->replace('.' . $extension, '.webp')->toString();
                    }, $object->gallery);
                }
                $object->save();
            });
        });
    }

    private function destinations(): void
    {
        Destination::withTrashed()->chunkById(100, function ($objects) {
            $objects->each(function (Destination $object) {
                if ($object->featured_image) {
                    $extension = str($object->featured_image)->explode('.')->last();
                    $object->featured_image = str($object->featured_image)->replace('.' . $extension, '.webp')->toString();
                }
                if ($object->banner) {
                    $extension = str($object->banner)->explode('.')->last();
                    $object->banner = str($object->banner)->replace('.' . $extension, '.webp')->toString();
                }
                if (!empty($object->gallery)) {
                    $object->gallery = array_map(function ($img) {
                        $extension = str($img)->explode('.')->last();
                        return str($img)->replace('.' . $extension, '.webp')->toString();
                    }, $object->gallery);
                }
                $object->save();
            });
        });
    }

    private function blogCategories(): void
    {
        BlogCategory::chunkById(100, function ($objects) {
            $objects->each(function (BlogCategory $object) {
                if ($object->featured_image) {
                    $extension = str($object->featured_image)->explode('.')->last();
                    $object->featured_image = str($object->featured_image)->replace('.' . $extension, '.webp')->toString();
                }
                $object->save();
            });
        });
    }
};

<?php

namespace App\Observers;

use App\Enums\BlogStatus;
use App\Models\Blog;

class BlogObserver
{
    /**
     * Handle the Blog "created" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function created(Blog $blog)
    {
        //
    }

    /**
     * Handle the Blog "updated" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function updated(Blog $blog)
    {
        if ($blog->wasChanged('status')) {
            if ($blog->status == BlogStatus::DRAFTED->value) {
                 $blog->fill([
                     'published_at' => null,
                     'published_by_id' => null,
                 ])->saveQuietly();
            }
            if ($blog->status == BlogStatus::PUBLISHED->value) {
                $blog->fill([
                    'published_at' => now(),
                    'published_by_id' => auth('web')->id(),
                ])->saveQuietly();
            }
        }
    }

    /**
     * Handle the Blog "deleted" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function deleted(Blog $blog)
    {
        //
    }

    /**
     * Handle the Blog "restored" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function restored(Blog $blog)
    {
        //
    }

    /**
     * Handle the Blog "force deleted" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function forceDeleted(Blog $blog)
    {
        //
    }
}

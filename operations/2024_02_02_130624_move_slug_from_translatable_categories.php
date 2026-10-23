<?php

use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;
use App\Models\Category;

return new class extends OneTimeOperation
{
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
        Category::withTrashed()->chunkById(10, function ($categories){
            $categories->each(function (Category $category){
                $category->update([
                    'slug' => $category->translations()->where('locale', 'en')->first()?->slug
                ]);
            });
        });
    }
};

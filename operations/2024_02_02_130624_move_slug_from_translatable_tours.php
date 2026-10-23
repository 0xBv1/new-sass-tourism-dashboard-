<?php

use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;
use App\Models\Tour;

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
        Tour::withTrashed()->chunkById(10, function ($tours){
            $tours->each(function (Tour $tour){
                $tour->update([
                    'slug' => $tour->translations()->where('locale', 'en')->first()?->slug
                ]);
            });
        });
    }
};

<?php

use App\Models\Seo;
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
        Seo::chunkById(100, function ($items) {
            $items->each(function (Seo $seo) {
                if ($seo->og_image) {
                    $extension = str($seo->og_image)->explode('.')->last();
                    $seo->og_image = str($seo->og_image)->replace('.' . $extension, '.webp')->toString();
                }

                if ($seo->twitter_image) {
                    $extension = str($seo->twitter_image)->explode('.')->last();
                    $seo->twitter_image = str($seo->twitter_image)->replace('.' . $extension, '.webp')->toString();
                }

                $seo->save();
            });
        });
    }
};

<?php

use App\Models\Page;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asynchronously.
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
        $aboutUs = Page::where('key', 'about-us')->first();
        $metas = [
            'about-sun-pyramids',
            'mission',
            'vision',
            'ceo-message',
        ];
        foreach ($metas as $meta) {
            $aboutUs->metas()->create([
                'meta_key' => $meta,
            ]);
        }
    }
};

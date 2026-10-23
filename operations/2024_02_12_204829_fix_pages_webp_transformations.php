<?php

use App\Models\Page;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

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
        Page::chunkById(100, function ($objects) {
            $objects->each(function (Page $object) {
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
};

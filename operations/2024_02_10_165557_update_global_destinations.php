<?php

use App\Models\Destination;
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
        $destinations = [
            'New York',
            'England',
            'India',
            'South Africa',
            'Morocco',
            'Jerusalem',
            'Jordan',
        ];
        foreach ($destinations as $dest) {
            if ($destination = Destination::whereTranslation('title', $dest)->first()) {
                $destination->update([
                    'global' => true
                ]);
            }
        }
    }
};

<?php

use App\Models\Tour;
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
        Tour::chunkById(100, function ($tours) {
            $tours->each(function (Tour $tour) {
                if ($tour->pricing_groups->isEmpty()) { return; }

                $groups = $tour->pricing_groups->toArray();

                foreach ($groups as $k => $group) {
                    $groups[$k]['child_price'] = $tour->child_price;
                }

                $res = $tour->forceFill([
                    'pricing_groups' => $groups
                ])->save();
                info('Tour: ' . $tour->id . ' ' . ($res ? 'Updated' : 'Failed'));
            });
        });
    }
};

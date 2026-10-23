<?php

namespace App\Observers;

use App\Models\TourReview;

class TourReviewObserver
{
    /**
     * Handle the TourReview "created" event.
     *
     * @param  \App\Models\TourReview  $tourReview
     * @return void
     */
    public function created(TourReview $tourReview)
    {
        //
    }

    /**
     * Handle the TourReview "updated" event.
     *
     * @param  \App\Models\TourReview  $tourReview
     * @return void
     */
    public function updated(TourReview $tourReview)
    {
        //
    }

    /**
     * Handle the TourReview "deleted" event.
     *
     * @param  \App\Models\TourReview  $tourReview
     * @return void
     */
    public function deleted(TourReview $tourReview)
    {
        //
    }

    /**
     * Handle the TourReview "restored" event.
     *
     * @param  \App\Models\TourReview  $tourReview
     * @return void
     */
    public function restored(TourReview $tourReview)
    {
        //
    }

    /**
     * Handle the TourReview "force deleted" event.
     *
     * @param  \App\Models\TourReview  $tourReview
     * @return void
     */
    public function forceDeleted(TourReview $tourReview)
    {
        //
    }
}

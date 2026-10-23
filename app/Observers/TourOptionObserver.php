<?php

namespace App\Observers;

use App\Models\TourOption;

class TourOptionObserver
{
    /**
     * Handle the TourOption "created" event.
     *
     * @param  \App\Models\TourOption  $tourOption
     * @return void
     */
    public function created(TourOption $tourOption)
    {
        //
    }

    /**
     * Handle the TourOption "updated" event.
     *
     * @param  \App\Models\TourOption  $tourOption
     * @return void
     */
    public function updated(TourOption $tourOption)
    {
        //
    }

    /**
     * Handle the TourOption "deleted" event.
     *
     * @param  \App\Models\TourOption  $tourOption
     * @return void
     */
    public function deleted(TourOption $tourOption)
    {
        //
    }

    /**
     * Handle the TourOption "restored" event.
     *
     * @param  \App\Models\TourOption  $tourOption
     * @return void
     */
    public function restored(TourOption $tourOption)
    {
        //
    }

    /**
     * Handle the TourOption "force deleted" event.
     *
     * @param  \App\Models\TourOption  $tourOption
     * @return void
     */
    public function forceDeleted(TourOption $tourOption)
    {
        //
    }
}

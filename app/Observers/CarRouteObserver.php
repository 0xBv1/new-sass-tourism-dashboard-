<?php

namespace App\Observers;

use App\Models\CarRoute;

class CarRouteObserver
{
    /**
     * Handle the CarRoute "created" event.
     *
     * @param  \App\Models\CarRoute  $carRoute
     * @return void
     */
    public function created(CarRoute $carRoute)
    {
        //
    }

    /**
     * Handle the CarRoute "updated" event.
     *
     * @param  \App\Models\CarRoute  $carRoute
     * @return void
     */
    public function updated(CarRoute $carRoute)
    {
        //
    }

    /**
     * Handle the CarRoute "deleted" event.
     *
     * @param  \App\Models\CarRoute  $carRoute
     * @return void
     */
    public function deleted(CarRoute $carRoute)
    {
        //
    }

    /**
     * Handle the CarRoute "restored" event.
     *
     * @param  \App\Models\CarRoute  $carRoute
     * @return void
     */
    public function restored(CarRoute $carRoute)
    {
        //
    }

    /**
     * Handle the CarRoute "force deleted" event.
     *
     * @param  \App\Models\CarRoute  $carRoute
     * @return void
     */
    public function forceDeleted(CarRoute $carRoute)
    {
        //
    }
}

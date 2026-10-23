<?php

namespace App\Observers;

use App\Models\CarRental;

class CarRentalObserver
{
    /**
     * Handle the CarRental "created" event.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return void
     */
    public function created(CarRental $carRental)
    {
        //
    }

    /**
     * Handle the CarRental "updated" event.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return void
     */
    public function updated(CarRental $carRental)
    {
        //
    }

    /**
     * Handle the CarRental "deleted" event.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return void
     */
    public function deleted(CarRental $carRental)
    {
        //
    }

    /**
     * Handle the CarRental "restored" event.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return void
     */
    public function restored(CarRental $carRental)
    {
        //
    }

    /**
     * Handle the CarRental "force deleted" event.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return void
     */
    public function forceDeleted(CarRental $carRental)
    {
        //
    }
}

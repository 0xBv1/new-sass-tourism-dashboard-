<?php

namespace App\Observers;

use App\Models\Amenity;

class AmenityObserver
{
    /**
     * Handle the Amenity "created" event.
     *
     * @param  \App\Models\Amenity  $amenity
     * @return void
     */
    public function created(Amenity $amenity)
    {
        //
    }

    /**
     * Handle the Amenity "updated" event.
     *
     * @param  \App\Models\Amenity  $amenity
     * @return void
     */
    public function updated(Amenity $amenity)
    {
        //
    }

    /**
     * Handle the Amenity "deleted" event.
     *
     * @param  \App\Models\Amenity  $amenity
     * @return void
     */
    public function deleted(Amenity $amenity)
    {
        //
    }

    /**
     * Handle the Amenity "restored" event.
     *
     * @param  \App\Models\Amenity  $amenity
     * @return void
     */
    public function restored(Amenity $amenity)
    {
        //
    }

    /**
     * Handle the Amenity "force deleted" event.
     *
     * @param  \App\Models\Amenity  $amenity
     * @return void
     */
    public function forceDeleted(Amenity $amenity)
    {
        //
    }
}

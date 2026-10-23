<?php

namespace App\Observers;

use App\Models\CustomizedTripCategory;

class CustomizedTripCategoryObserver
{
    /**
     * Handle the CustomizedTripCategory "created" event.
     *
     * @param  \App\Models\CustomizedTripCategory  $customizedTripCategory
     * @return void
     */
    public function created(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }

    /**
     * Handle the CustomizedTripCategory "updated" event.
     *
     * @param  \App\Models\CustomizedTripCategory  $customizedTripCategory
     * @return void
     */
    public function updated(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }

    /**
     * Handle the CustomizedTripCategory "deleted" event.
     *
     * @param  \App\Models\CustomizedTripCategory  $customizedTripCategory
     * @return void
     */
    public function deleted(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }

    /**
     * Handle the CustomizedTripCategory "restored" event.
     *
     * @param  \App\Models\CustomizedTripCategory  $customizedTripCategory
     * @return void
     */
    public function restored(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }

    /**
     * Handle the CustomizedTripCategory "force deleted" event.
     *
     * @param  \App\Models\CustomizedTripCategory  $customizedTripCategory
     * @return void
     */
    public function forceDeleted(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }
}

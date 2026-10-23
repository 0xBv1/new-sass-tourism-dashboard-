<?php

namespace App\Observers;

use App\Models\Tour;
use App\Models\Category;

class TourObserver
{
    /**
     * Handle the Tour "created" event.
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    public function created(Tour $tour)
    {
        $this->updateCategoriesToursCount($tour);
    }

    /**
     * Handle the Tour "updated" event.
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    public function updated(Tour $tour)
    {
        // Check if enabled status changed
        if ($tour->isDirty('enabled')) {
            $this->updateCategoriesToursCount($tour);
        }
    }

    /**
     * Handle the Tour "deleted" event.
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    public function deleted(Tour $tour)
    {
        $this->updateCategoriesToursCount($tour);
    }

    /**
     * Handle the Tour "restored" event.
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    public function restored(Tour $tour)
    {
        $this->updateCategoriesToursCount($tour);
    }

    /**
     * Handle the Tour "force deleted" event.
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    public function forceDeleted(Tour $tour)
    {
        $this->updateCategoriesToursCount($tour);
    }

    /**
     * Update tours count for all categories associated with the tour
     *
     * @param  \App\Models\Tour  $tour
     * @return void
     */
    private function updateCategoriesToursCount(Tour $tour)
    {
        $categoryIds = $tour->categories()->pluck('categories.id');
        
        foreach ($categoryIds as $categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $category->setToursCount();
            }
        }
    }
}

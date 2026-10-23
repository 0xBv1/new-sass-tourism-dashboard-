<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TourCategoryPivotObserver
{
    /**
     * Handle the pivot "attached" event.
     *
     * @param  \App\Models\Category  $category
     * @param  array  $ids
     * @param  array  $attributes
     * @return void
     */
    public function pivotAttached(Category $category, array $ids, array $attributes)
    {
        $category->setToursCount();
    }

    /**
     * Handle the pivot "detached" event.
     *
     * @param  \App\Models\Category  $category
     * @param  array  $ids
     * @return void
     */
    public function pivotDetached(Category $category, array $ids)
    {
        $category->setToursCount();
    }

    /**
     * Handle the pivot "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @param  array  $ids
     * @param  array  $attributes
     * @return void
     */
    public function pivotUpdated(Category $category, array $ids, array $attributes)
    {
        $category->setToursCount();
    }
}

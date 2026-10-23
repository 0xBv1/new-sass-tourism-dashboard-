<?php

namespace App\Observers;

use App\Models\Faq;

class FaqObserver
{
    /**
     * Handle the Faq "created" event.
     *
     * @param  \App\Models\Faq  $faq
     * @return void
     */
    public function created(Faq $faq)
    {
        //
    }

    /**
     * Handle the Faq "updated" event.
     *
     * @param  \App\Models\Faq  $faq
     * @return void
     */
    public function updated(Faq $faq)
    {
        //
    }

    /**
     * Handle the Faq "deleted" event.
     *
     * @param  \App\Models\Faq  $faq
     * @return void
     */
    public function deleted(Faq $faq)
    {
        //
    }

    /**
     * Handle the Faq "restored" event.
     *
     * @param  \App\Models\Faq  $faq
     * @return void
     */
    public function restored(Faq $faq)
    {
        //
    }

    /**
     * Handle the Faq "force deleted" event.
     *
     * @param  \App\Models\Faq  $faq
     * @return void
     */
    public function forceDeleted(Faq $faq)
    {
        //
    }
}

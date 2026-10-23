<?php

namespace App\Observers;

use App\Models\RedirectRule;

class RedirectRuleObserver
{
    /**
     * Handle the RedirectRule "created" event.
     *
     * @param  \App\Models\RedirectRule  $redirectRule
     * @return void
     */
    public function created(RedirectRule $redirectRule)
    {
        //
    }

    /**
     * Handle the RedirectRule "updated" event.
     *
     * @param  \App\Models\RedirectRule  $redirectRule
     * @return void
     */
    public function updated(RedirectRule $redirectRule)
    {
        //
    }

    /**
     * Handle the RedirectRule "deleted" event.
     *
     * @param  \App\Models\RedirectRule  $redirectRule
     * @return void
     */
    public function deleted(RedirectRule $redirectRule)
    {
        //
    }

    /**
     * Handle the RedirectRule "restored" event.
     *
     * @param  \App\Models\RedirectRule  $redirectRule
     * @return void
     */
    public function restored(RedirectRule $redirectRule)
    {
        //
    }

    /**
     * Handle the RedirectRule "force deleted" event.
     *
     * @param  \App\Models\RedirectRule  $redirectRule
     * @return void
     */
    public function forceDeleted(RedirectRule $redirectRule)
    {
        //
    }
}

<?php

namespace App\Traits\Models;
use App\Scopes\RestrictClient as RestrictClientScope;

trait RestrictClient
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new RestrictClientScope);
    }
}

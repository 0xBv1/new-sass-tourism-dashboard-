<?php

namespace App\Traits\Models;

use App\Scopes\Activated as Scope;

trait Activated
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new Scope);
    }

    public function scopeActive($query, $active = true)
    {
        return $query->where('active', $active);
    }
}

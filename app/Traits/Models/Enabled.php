<?php

namespace App\Traits\Models;

use App\Scopes\Enabled as Scope;

trait Enabled
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
        return $query->where('enabled', $active);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', false);
    }
}

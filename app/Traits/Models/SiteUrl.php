<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait SiteUrl
{
    public function siteUrl(): Attribute
    {
        return new Attribute(
            get: fn() =>  site_url('/'. str($this->getTable())->singular()->lower() .'/' . $this->slug)
        );
    }
}

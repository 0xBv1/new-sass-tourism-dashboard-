<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait FixDescriptionImagesLink
{
    public function description(): Attribute
    {
        return new Attribute(
            get: fn($value) => !empty($value) ? str($value)->replace('https://sunpyramidstours.com/wp-content', asset('storage/media/wordpress-media')) : $value
        );
    }
}

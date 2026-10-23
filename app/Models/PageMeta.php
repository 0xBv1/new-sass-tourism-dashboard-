<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $meta_value
*/
class PageMeta extends Model
{
    use Translatable;

    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'meta_key',
    ];
    protected $hidden = ['translations'];

    public $translatedAttributes = [
        'meta_value',
    ];
}

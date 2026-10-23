<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class FaqCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];
}


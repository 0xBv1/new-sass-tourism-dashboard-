<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Activated implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Str::of(Route::currentRouteName())->startsWith('api.')) {
            $builder->where($model->getTable().'.active', true);
        }
    }
}

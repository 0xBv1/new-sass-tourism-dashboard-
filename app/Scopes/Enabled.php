<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Enabled implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (collect(request()->segments())->first() == 'api') {
            $builder->where($model->getTable() . '.enabled', true);
        }
    }
}

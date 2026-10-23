<?php

namespace App\Scopes;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RestrictClient implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->user() instanceof Client) {
            $builder->where('client_id', auth()->id());
        }
    }
}

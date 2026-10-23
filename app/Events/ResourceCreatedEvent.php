<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Str;

class ResourceCreatedEvent
{
    use Dispatchable;

    public function __construct(Model $model)
    {
         $modelJob = '\App\Jobs\Translate'.Str::of(class_basename($model))->plural().'Job';
         if (class_exists($modelJob) && $model->wasRecentlyCreated) {
             $modelJob::dispatch([$model->id]);
         }
    }
}

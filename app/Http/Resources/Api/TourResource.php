<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;
use App\Models\Tour;


/**
 * @property Tour $resource
 */
class TourResource extends JsonResource
{
    protected array $relations = [
        'options' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\TourOptionResource'],
        'destinations' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\DestinationResource'],
        'categories' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\CategoryResource'],
        'seasons' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\TourSeasonResource'],
    ];
}

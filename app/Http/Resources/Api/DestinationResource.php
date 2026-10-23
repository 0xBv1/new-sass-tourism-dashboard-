<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;
use App\Models\Destination;

/**
 * @property Destination $resource
 */
class DestinationResource extends JsonResource
{
    protected array $relations = [
        'tours' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\TourResource'],
        'children' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\DestinationResource'],
        'seo' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\SeoResource'],
        'parent' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\DestinationResource'],
    ];
}

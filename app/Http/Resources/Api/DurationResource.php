<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;
use App\Models\Duration;

/**
 * @property Duration $resource
 */
class DurationResource extends JsonResource
{
    protected array $relations = [
        'tours' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\TourResource'],
        'children' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\DurationResource'],
        'seo' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\SeoResource'],
        'parent' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\DurationResource'],
    ];
} 
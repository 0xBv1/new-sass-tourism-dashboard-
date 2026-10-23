<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;
use App\Models\Category;

/**
 * @property Category $resource
 */
class CategoryResource extends JsonResource
{
    protected array $relations = [
        'tours' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\TourResource'],
        'children' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\CategoryResource'],
        'seo' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\SeoResource'],
        'parent' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\CategoryResource'],
    ];

}

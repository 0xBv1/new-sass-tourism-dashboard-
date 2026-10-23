<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;

class PageResource extends JsonResource
{
    protected array $relations = [
        'seo' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\SeoResource'],
    ];
}

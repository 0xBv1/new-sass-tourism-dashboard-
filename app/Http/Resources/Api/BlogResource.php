<?php

namespace App\Http\Resources\Api;


use App\Http\Resources\JsonResource;

class BlogResource extends JsonResource
{
    protected array $relations = [
        'seo' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\SeoResource'],
        'category' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\BlogCategoryResource'],
        'comments' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\BlogCommentResource'],
        'approvedComments' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\BlogCommentResource'],
    ];
}

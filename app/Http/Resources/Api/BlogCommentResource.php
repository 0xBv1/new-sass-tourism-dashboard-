<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\JsonResource;

class BlogCommentResource extends JsonResource
{
    protected array $relations = [
        'blog' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\BlogResource'],
        'client' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\ClientResource'],
        'parent' => ['type' => 'single', 'resourceClass' => '\App\Http\Resources\Api\BlogCommentResource'],
        'replies' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\BlogCommentResource'],
        'approvedReplies' => ['type' => 'collection', 'resourceClass' => '\App\Http\Resources\Api\BlogCommentResource'],
    ];

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'blog_id' => $this->blog_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'email' => $this->email,
            'comment' => $this->comment,
            'is_approved' => $this->is_approved,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'blog' => $this->whenLoaded('blog'),
            'client' => $this->whenLoaded('client'),
            'parent' => $this->whenLoaded('parent'),
            'replies' => $this->whenLoaded('replies'),
            'approved_replies' => $this->whenLoaded('approvedReplies'),
            'replies_count' => $this->when(isset($this->replies_count), $this->replies_count),
            'approved_replies_count' => $this->whenLoaded('approvedReplies', function () {
                return $this->approvedReplies->count();
            }),
        ];
    }
}

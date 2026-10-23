<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'stars' => $this->stars,
            'is_featured' => $this->is_featured,
            'tour' => $this->when($this->relationLoaded('tour'), function () {
                return [
                    'id' => $this->tour->id,
                    'title' => $this->tour->title,
                    'slug' => $this->tour->slug,
                ];
            }),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}


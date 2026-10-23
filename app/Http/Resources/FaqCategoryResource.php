<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\FaqResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'faqs' => FaqResource::collection($this->whenLoaded('activeFaqs')),
            'faqs_count' => $this->faqs_count,
        ];
    }
}


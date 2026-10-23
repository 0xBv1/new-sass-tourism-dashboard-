<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'image' => $this->image_url,
            'mobile_image' => $this->mobile_image_url,
            'link' => $this->link,
            'button_text' => $this->button_text,
            'button_style' => $this->button_style,
            'has_button' => $this->hasButton(),
            'order' => $this->order,
        ];
    }
}


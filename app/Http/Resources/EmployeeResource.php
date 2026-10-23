<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio,
            'image' => $this->image_url,
            'email' => $this->email,
            'phone' => $this->phone,
            'social_links' => [
                'facebook' => $this->facebook_link,
                'twitter' => $this->twitter_link,
                'linkedin' => $this->linkedin_link,
                'instagram' => $this->instagram_link,
            ],
            'has_social_links' => $this->hasSocialLinks(),
        ];
    }
}


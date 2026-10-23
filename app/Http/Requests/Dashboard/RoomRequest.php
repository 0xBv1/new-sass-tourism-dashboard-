<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
    {
        $attributes = [
            "amenities" => "Amenities",
            "slug" => "Slug",
            "featured_image" => "Featured Image",
            "banner" => "Banner",
            "gallery" => "Gallery",
            "enabled" => "Enabled",
            "bed_count" => "Bed Count",
            "room_type" => "Room Type",
            "max_capacity" => "Max Capacity",
            "bed_types" => "Bed Types",
            "night_price" => "Night Price",
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".name"] = $local["native"] . " Name";
            $attributes[$localKey . ".description"] = $local["native"] . " Description";
            $attributes['seo.' . $localKey . ".meta_title"] = $local["native"] . " Meta Title";
            $attributes['seo.' . $localKey . ".meta_description"] = $local["native"] . " Meta Description";
            $attributes['seo.' . $localKey . ".meta_keywords"] = $local["native"] . " Meta Keywords";
            $attributes['seo.' . $localKey . ".og_title"] = $local["native"] . " Open Graph Title";
            $attributes['seo.' . $localKey . ".og_description"] = $local["native"] . " Open Graph Description";
            $attributes['seo.' . $localKey . ".canonical"] = $local["native"] . " Canonical";
        }
        return $attributes;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'slug' => ['required', Rule::unique('rooms')->ignore(request('room'))],
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'banner' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:255'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', 'integer', 'exists:amenities,id'],
            'enabled' => ['nullable'],
            'bed_count' => ['integer', 'min:1'],
            'room_type' => ['nullable', 'string', 'max:255'],
            'max_capacity' => ['nullable', 'integer', 'min:0'],
            'bed_types' => ['nullable', 'string', 'max:255'],
            'night_price' => ['required', 'numeric', 'min:0'],
            'children' => ['nullable', 'array'],
            'children.*.age_range' => ['required', 'string', 'max:255'],
            'children.*.max_count' => ['required', 'integer', 'min:1'],
            'children.*.price' => ['required', 'numeric', 'min:0'],
            'children.*.description' => ['nullable', 'string'],
            'children.*.enabled' => ['nullable', 'boolean'],
            'adults' => ['nullable', 'array'],
            'adults.*.age_range' => ['required', 'string', 'max:255'],
            'adults.*.max_count' => ['required', 'integer', 'min:1'],
            'adults.*.price' => ['required', 'numeric', 'min:0'],
            'adults.*.description' => ['nullable', 'string'],
            'adults.*.enabled' => ['nullable', 'boolean'],
        ];

        foreach (config('translatable.locales') as $local) {
            $rules["$local.name"] = [($local == config("app.locale") ? "required" : "nullable"), 'string', 'max:255'];
            $rules["$local.description"] = ["nullable"];
            $rules["seo.$local.meta_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.meta_description"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.meta_keywords"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.og_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.og_description"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.canonical"] = ["nullable", 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $data = $this->validated();
        $data['enabled'] = $this->boolean('enabled');
        unset($data['seo']);
        unset($data['amenities']);
        return $data;
    }
}

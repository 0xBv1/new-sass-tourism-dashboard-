<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
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
            "stars" => "Stars",
            "enabled" => "Enabled",
            "featured_image" => "Featured Image",
            "banner" => "Banner",
            "gallery" => "Gallery",
            "address" => "Address",
            "map_iframe" => "Map Iframe",
            "slug" => "Slug",
            "phone_contact" => "Phone Contact",
            "whatsapp_contact" => "Whatsapp Contact",
            "room_count" => "Number of Rooms",
            "adult_prices" => "Adult Prices",
            "adult_prices.*.count" => "Number of Adults",
            "adult_prices.*.price" => "Adult Price",
            "adult_prices.*.description" => "Adult Price Description",
            "child_prices" => "Child Prices",
            "child_prices.*.count" => "Number of Children",
            "child_prices.*.price" => "Child Price",
            "child_prices.*.description" => "Child Price Description",
            "meal_types" => "Meal Types",
            "meal_types.*.type" => "Meal Type",
            "meal_types.*.price" => "Meal Price",
            "meal_types.*.description" => "Meal Description",
            "meal_types.*.images" => "Meal Images",
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
            'slug' => ['required', 'string', Rule::unique('hotels')->ignore(request('hotel'))],
            'stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            'enabled' => ['nullable'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'banner' => ['nullable', 'string', 'max:255'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', 'integer', 'exists:amenities,id'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string'],
            'address' => ['nullable', 'string', 'max:255'],
            'map_iframe' => ['nullable', 'string'],
            'phone_contact' => ['nullable', 'string', 'max:255'],
            'whatsapp_contact' => ['nullable', 'string', 'max:255'],
            'room_count' => ['required', 'integer', 'min:1', 'max:50'],
            'adult_prices' => ['required', 'array', 'min:1'],
            'adult_prices.*.count' => ['required', 'integer', 'min:1', 'max:20'],
            'adult_prices.*.price' => ['required', 'numeric', 'min:0'],
            'adult_prices.*.description' => ['nullable', 'string', 'max:255'],
            'child_prices' => ['required', 'array', 'min:1'],
            'child_prices.*.count' => ['required', 'integer', 'min:1', 'max:20'],
            'child_prices.*.price' => ['required', 'numeric', 'min:0'],
            'child_prices.*.description' => ['nullable', 'string', 'max:255'],
            'meal_types' => ['nullable', 'array'],
            'meal_types.*.type' => ['required_with:meal_types', 'string', 'in:breakfast,lunch,dinner,brunch,high_tea,snacks,beverages,desserts,buffet'],
            'meal_types.*.price' => ['required_with:meal_types', 'numeric', 'min:0'],
            'meal_types.*.description' => ['nullable', 'string'],
            'meal_types.*.images' => ['nullable', 'array', 'max:3'],
            'meal_types.*.images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'seo' => ['nullable', 'array'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.og_type' => ['nullable', 'string', 'max:255'],
            'seo.viewport' => ['nullable', 'string', 'max:255'],
            'seo.robots' => ['nullable', 'string', 'max:255'],

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
        $data['enabled']= $this->boolean('enabled');
        unset($data['seo']);
        unset($data['amenities']);
        unset($data['room_count']);
        unset($data['adult_prices']);
        unset($data['child_prices']);
        unset($data['meal_types']);
        return $data;
    }
}

<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourRequest extends FormRequest
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
            "enabled" => "Enabled",
            "featured" => "Featured",
            "slug" => "Slug",
            "featured_image" => "Featured Image",
            "gallery" => "Gallery",
            "days" => "Tour Days",
            "adult_price" => "Adult Price",
            "infant_price" => "Infant Price",
            "child_price" => "Child Price",
            "pricing_groups" => "Pricing Groups",
            "categories" => "Categories",
            "duration_in_days" => "Duration in days",
            "destinations" => "Destinations",
            "options" => "Tour Options",
        ];
        for ($i = 0; $i < $this->collect('pricing_groups')->count(); $i++) {
            $attributes['pricing_groups.' . $i . ".from"] = "Group Price From at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".to"] = "Group Price To at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".price"] = "Group Adult Price at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".child_price"] = "Group Child Price at " . ($i + 1);
        }

        for ($i = 0; $i < $this->collect('seasons')->count(); $i++) {
            $attributes['seasons.' . $i . ".start_day"] = "Season Start Day " . ($i + 1);
            $attributes['seasons.' . $i . ".start_month"] = "Season Start Month " . ($i + 1);
            $attributes['seasons.' . $i . ".end_day"] = "Season End Day " . ($i + 1);
            $attributes['seasons.' . $i . ".end_month"] = "Season End Month " . ($i + 1);
            $attributes['seasons.' . $i . ".pricing_groups"] = "Season Pricing Groups " . ($i + 1);

            for ($j = 0; $j < count($this->get('seasons')[$i]['pricing_groups'] ?? []); $j++) {
                $attributes['seasons.' . $i . ".pricing_groups.".$j. ".from"] = "Season " . ($i + 1) . ' Price Group From '. ($j + 1);
                $attributes['seasons.' . $i . ".pricing_groups.".$j. ".to"] = "Season " . ($i + 1) . ' Price Group To '. ($j + 1);
                $attributes['seasons.' . $i . ".pricing_groups.".$j. ".price"] = "Season " . ($i + 1) . ' Price Group Adult Price '. ($j + 1);
                $attributes['seasons.' . $i . ".pricing_groups.".$j. ".child_price"] = "Season " . ($i + 1) . ' Price Group Child Price '. ($j + 1);
            }
        }

        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".title"] = $local["native"] . " Title";
            $attributes[$localKey . ".overview"] = $local["native"] . " Overview";
            $attributes[$localKey . ".highlights"] = $local["native"] . " Highlights";
            $attributes[$localKey . ".excluded"] = $local["native"] . " Excluded";
            $attributes[$localKey . ".included"] = $local["native"] . " Included";
            $attributes[$localKey . ".duration"] = $local["native"] . " Duration";
            $attributes[$localKey . ".type"] = $local["native"] . " Type";
            $attributes[$localKey . ".run"] = $local["native"] . " Run";
            $attributes[$localKey . ".pickup_time"] = $local["native"] . " PickupTime";
            $attributes['seo.' . $localKey . ".meta_title"] = $local["native"] . " Meta Title";
            $attributes['seo.' . $localKey . ".meta_description"] = $local["native"] . " Meta Description";
            $attributes['seo.' . $localKey . ".meta_keywords"] = $local["native"] . " Meta Keywords";
            $attributes['seo.' . $localKey . ".og_title"] = $local["native"] . " Open Graph Title";
            $attributes['seo.' . $localKey . ".og_description"] = $local["native"] . " Open Graph Description";
            $attributes['seo.' . $localKey . ".viewport"] = $local["native"] . " Viewport";
            $attributes['seo.' . $localKey . ".robots"] = $local["native"] . " Robots";
            for ($i = 0; $i < $this->collect('days')->count(); $i++) {
                $attributes['days.' . $i . '.' . $localKey . ".title"] = $local["native"] . " Day Title at " . ($i + 1);
                $attributes['days.' . $i . '.' . $localKey . ".description"] = $local["native"] . " Day Description at " . ($i + 1);
            }
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
            'code' => ['nullable', 'string', Rule::unique('tours')->ignore(request('tour'))],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tours')->whereNull('tours.deleted_at')->ignore(request('tour'))],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'enabled' => ['nullable'],
            'featured' => ['nullable'],
            'featured_image' => ['nullable'],
            'duration_in_days' => ['nullable', 'numeric', 'min:0'],
            'adult_price' => ['nullable', 'numeric', 'min:0'],
            'infant_price' => ['nullable', 'numeric', 'min:0'],
            'child_price' => ['nullable', 'numeric', 'min:0'],
            'pricing_groups' => ['nullable', 'array'],
            'pricing_groups.*.from' => ['integer', 'min:1'],
            'pricing_groups.*.to' => ['integer', 'min:1'],
            'pricing_groups.*.price' => ['numeric', 'min:0'],
            'pricing_groups.*.child_price' => ['numeric', 'min:0'],
            'gallery' => ['nullable', 'array'],
            'options' => ['nullable', 'array'],
            'options.*' => ['integer', 'exists:tour_options,id'],
            'seo' => ['nullable', 'array'],
            'days' => ['nullable', 'array', 'min:1'],
            'days.0.en.title' => ['nullable', 'string'],
            'days.0.en.description' => ['nullable', 'string'],
            'categories' => ['nullable', 'min:1', 'array'],
            'categories.*' => ['nullable', 'integer', 'exists:categories,id'],
            'destinations' => ['nullable', 'min:1', 'array'],
            'destinations.*' => ['nullable', 'integer', 'exists:destinations,id'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.viewport' => ['nullable', 'string', 'max:255'],
            'seo.robots' => ['nullable', 'string', 'max:255'],
            'seo.og_type' => ['nullable', 'string', 'max:255'],
            'seasons' => ['nullable', 'array'],
            'seasons.*.title' => ['nullable', 'string', 'max:255'],
            'seasons.*.start_day' => ['nullable', 'integer', 'between:1,31'],
            'seasons.*.start_month' => ['nullable', 'integer', 'between:1,12'],
            'seasons.*.end_day' => ['nullable', 'integer', 'between:1,31'],
            'seasons.*.end_month' => ['nullable', 'integer', 'between:1,12'],
            'seasons.*.enabled' => ['nullable'],
            'seasons.*.pricing_groups' => [$this->has('seasons') ? 'nullable' : 'nullable', 'array', 'min:1'],
            'seasons.*.pricing_groups.*.from' => ['nullable', 'integer', 'min:1'],
            'seasons.*.pricing_groups.*.to' => ['nullable', 'integer', 'min:1'],
            'seasons.*.pricing_groups.*.adult_price' => ['nullable', 'numeric', 'min:0'],
            'seasons.*.pricing_groups.*.child_price' => ['nullable', 'numeric', 'min:0'],
        ];

        foreach (config('translatable.locales') as $local) {
            $rules["$local.title"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.overview"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.highlights"] = [$local == config("app.locale") ? "nullable" : "nullable"];

            $rules["$local.included"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.excluded"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.duration"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.type"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.run"] = [$local == config("app.locale") ? "nullable" : "nullable"];
            $rules["$local.pickup_time"] = [$local == config("app.locale") ? "nullable" : "nullable"];

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
     * @return array
     */
    public function getSanitized(): array
    {
        $data = $this->validated();
        $data['enabled'] = $this->boolean('enabled');
        $data['featured'] = $this->boolean('featured');
        $data['pricing_groups'] = array_values($this->get('pricing_groups') ?? []);
        unset($data['seo'], $data['seasons'], $data['categories'], $data['destinations']);
        return $data;
    }
}

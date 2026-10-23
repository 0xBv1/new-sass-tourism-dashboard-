<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
            "key" => "Key",
            "gallery" => "Gallery",
            "mobile_gallery" => "Mobile Gallery",
            "banner" => "Banner",
            "seo.og_image" => "Open Graph",
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".title"] = $local["native"] . " Title";
            $attributes[$localKey . ".short_description"] = $local["native"] . " Short Description";
            $attributes[$localKey . ".content"] = $local["native"] . " Content";
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
            'key' => ['required', 'alpha_dash', Rule::unique('pages')->ignore(request('page'))],
            'banner' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'mobile_gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string'],
            'mobile_gallery.*' => ['nullable', 'string'],
            'seo' => ['nullable', 'array'],
            'meta' => ['nullable', 'array'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.og_type' => ['nullable', 'string', 'max:255'],
        ];
        foreach (config('translatable.locales') as $local) {
            $rules["$local.title"] = [$local == config("app.locale") ? "required" : "nullable"];
            $rules["$local.content"] = ["nullable"];
            $rules["$local.short_description"] = ["nullable"];
            $rules["seo.$local.meta_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.meta_description"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.meta_keywords"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.og_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.og_description"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.canonical"] = ["nullable", 'string', 'max:255'];
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
        unset($data['seo']);
        if ($this->isMethod('PUT')) {
            if (in_array(request('page')->key, Page::MAIN_PAGES)) {
                unset($data['key']);
            }
        }
        return $data;
    }
}

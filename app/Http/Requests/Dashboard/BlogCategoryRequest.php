<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
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
            "active" => "Active",
            "parent_id" => "Parent",
            "slug" => "Slug",
            "featured_image" => "Featured Image",
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".title"] = $local["native"] . " Title";
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
            'active' => ['nullable'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('blog_categories')->ignore(request('blog_category'))],
            'parent_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'related_tours' => ['nullable', 'array', 'max:20'],
            'related_tours.*' => ['nullable', 'integer', 'exists:tours,id'],
            'featured_image' => ['nullable'],
            'seo' => ['nullable', 'array'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.og_type' => ['nullable', 'string', 'max:255'],
            'seo.viewport' => ['nullable', 'string'],
            'seo.robots' => ['nullable', 'string'],

        ];
        foreach (config('translatable.locales') as $local) {
            $rules["$local.title"] = [$local == config("app.locale") ? "required" : "nullable"];
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
        $data['active'] = $this->filled('active');
        unset($data['seo'], $data['related_tours']);
        return $data;
    }
}

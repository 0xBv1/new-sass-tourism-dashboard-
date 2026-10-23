<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\BlogStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
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
            "featured_image" => "Featured Image",
            "category_id" => "Blog Category",
            "slug" => "Slug",
            "gallery" => "Gallery",
            "active" => "Active",
            "published_at" => "PublishedAt",
            "status" => "Status",
            "seo.og_image" => "Open Graph",
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".title"] = $local["native"] . " Title";
            $attributes[$localKey . ".description"] = $local["native"] . " Description";
            $attributes[$localKey . ".tags"] = $local["native"] . " Tags";
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
        if ($this->get('action') == 'UPDATE_PUBLISH') {
            return [
                'status' => ['required', Rule::in(BlogStatus::all())]
            ];
        }
        $rules = [
            'status' => ['required', Rule::in(BlogStatus::all())],
            'slug' => ['required', 'string', 'max:255', Rule::unique('blogs')->whereNull('blogs.deleted_at')->ignore(request('blog'))],
            'featured_image' => ['nullable'],
            'gallery' => ['nullable', 'array'],
            'related_tours' => ['nullable', 'array', 'max:20'],
            'related_tours.*' => ['nullable', 'integer', 'exists:tours,id'],
            'active' => ['nullable'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:blog_categories,id'],
            'seo' => ['nullable', 'array'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.og_type' => ['nullable', 'string', 'max:255'],
            'seo.viewport' => ['nullable', 'string', 'max:255'],
            'seo.robots' => ['nullable', 'string', 'max:255'],
            'display_order'=> ['nullable','integer']
        ];

        if ($this->filled('action')) {
            return $rules;
        }

        foreach (config('translatable.locales') as $local) {
            $rules["$local.title"] = [$local == config("app.locale") ? "required" : "nullable"];
            $rules["$local.description"] = ["nullable"];
            $rules["$local.tags"] = [$local == config("app.locale") ? "required" : "nullable"];
            $rules["seo.$local.meta_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.meta_description"] = ["nullable", 'string', 'max:1000'];
            $rules["seo.$local.meta_keywords"] = ["nullable", 'string', 'max:1000'];
            $rules["seo.$local.og_title"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.og_description"] = ["nullable", 'string', 'max:1000'];
            $rules["seo.$local.canonical"] = ["nullable", 'string', 'max:255'];
            $rules["seo.$local.twitter_description"] = ["nullable", 'string', 'max:1000'];
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
        if ($this->filled('action')) {
            return $this->only('status');
        }
        $data = $this->validated();
        $data['active'] = $this->boolean('active');
        unset($data['seo'],$data['related_tours'], $data['categories']);
        return $data;
    }
}

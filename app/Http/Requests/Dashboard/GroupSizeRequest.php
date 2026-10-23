<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupSizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function attributes(): array
    {
        $attributes = [
            "slug" => "Slug",
            "min_people" => "Min People",
            "max_people" => "Max People",
            "display_order" => "Display Order",
            "enabled" => "Enabled",
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".name"] = $local["native"] . " Name";
        }
        return $attributes;
    }

    public function rules(): array
    {
        $rules = [
            'slug' => ['required', 'string', 'max:255', Rule::unique('group_sizes')->ignore(request('group_size'))],
            'min_people' => ['nullable', 'integer', 'min:1'],
            'max_people' => ['nullable', 'integer', 'min:1', 'gte:min_people'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'enabled' => ['nullable'],
        ];

        foreach (config('translatable.locales') as $local) {
            $rules["$local.name"] = [($local == config("app.locale") ? "required" : "nullable"), 'string', 'max:255'];
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
        return $data;
    }
}

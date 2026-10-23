<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
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
        ];
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".name"] = $local["native"] . " Name";
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
        ];
        foreach (config('translatable.locales') as $local) {
            $rules["$local.name"] = [
                $local == config("app.locale") ? "required" : "nullable",
                Rule::unique('location_translations', 'name')
                    ->where('locale', $local)
                    ->ignore(
                        request('location')?->translations?->firstWhere('locale', $local)?->id
                    )
            ];
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
        $data['active'] = $this->boolean('active');
        return $data;
    }
}

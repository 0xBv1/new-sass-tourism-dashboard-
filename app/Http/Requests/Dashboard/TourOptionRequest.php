<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourOptionRequest extends FormRequest
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
            "adult_price" => "Adult Price",
            "child_price" => "Child Price",
            "pricing_groups" => "Pricing Groups",
        ];
        for ($i = 0; $i < $this->collect('pricing_groups')->count(); $i++) {
            $attributes['pricing_groups.' . $i . ".from"] = "Group Price From at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".to"] = "Group Price To at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".price"] = "Group Adult Price at " . ($i + 1);
            $attributes['pricing_groups.' . $i . ".child_price"] = "Group Child Price at " . ($i + 1);
        }
        foreach (config('translatable.supported_locales') as $localKey => $local) {
            $attributes[$localKey . ".name"] = $local["native"] . " Name";
            $attributes[$localKey . ".description"] = $local["native"] . " Description";
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
            'adult_price' => ['required', 'numeric', 'min:0'],
            'child_price' => ['required', 'numeric', 'min:0'],
            'pricing_groups' => ['nullable', 'array'],
            'pricing_groups.*.from' => ['integer', 'min:1'],
            'pricing_groups.*.to' => ['integer', 'min:1'],
            'pricing_groups.*.price' => ['numeric', 'min:0'],
            'pricing_groups.*.child_price' => ['numeric', 'min:0'],
        ];
        foreach (config('translatable.locales') as $local) {
            $rules["$local.name"] = [
                $local == config("app.locale") ? "required" : "nullable", 'string', 'max:255',
                Rule::unique('tour_option_translations', 'name')
                    ->where('locale', $local)
                    ->ignore(
                        request('tour_option')?->translations?->firstWhere('locale', $local)?->id
                    )
            ];
            $rules["$local.description"] = ["nullable", 'string', 'max:500'];
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
        if (!$this->has('pricing_groups')) {
            $data['pricing_groups'] = null;
        }
        return $data;
    }
}

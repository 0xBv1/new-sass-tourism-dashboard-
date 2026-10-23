<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RedirectRuleRequest extends FormRequest
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

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'source.regex' => 'The :attribute must be a valid path.',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            "source" => "Source",
            "destination" => "Destination",
            "enabled" => "Enabled",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'source' => ['required', 'different:destination', Rule::unique('redirect_rules')->ignore(request('redirect_rule')), 'string', 'min:1', 'regex:/^(\/[^\s]*)?$/i'],
            'destination' => ['required', 'different:source', 'string', 'min:1', 'regex:/^(\/[^\s]*)?$/i'],
            'enabled' => ['nullable'],
        ];
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

<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            "active" => "Active",
            "name" => "Name",
            "symbol" => "Symbol",
            "exchange_rate" => "ExchangeRate",
            "icon" => "Icon",
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
            'active' => ['nullable'],
            'name' => [
                $this->isMethod('PUT') ? 'nullable' : 'required', 'string', 'size:3',
                //Rule::in(Setting::key('allowed_currencies')->first()->option_value->toArray()),
                Rule::unique('currencies')->ignore(request('currency'))
            ],
            'symbol' => ['required', 'string'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'icon' => ['nullable', 'string'],
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
        $data['active'] = $this->filled('active');
        if ($this->isMethod('PUT')) {
            unset($data['name']);
        }
        return $data;
    }
}

<?php

namespace App\Http\Requests\Api\CarRental;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
            'destination_id' => ['required', 'integer', 'exists:locations,id'],
            'adults' => ['required', 'integer'],
            'children' => ['required', 'integer'],
            'oneway' => ['required', 'boolean'],
            'pickup_date' => ['required', 'date'],
            'pickup_time' => ['required', 'regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'return_date' => [$this->get('oneway') ? 'nullable' :'required', 'date'],
            'return_time' => [$this->get('oneway') ? 'nullable' :'required', 'regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'name' => ['nullable', 'string', 'max:254'],
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:254'],
            'nationality' => ['nullable', 'string', 'max:254'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'stops' => ['nullable', 'array', 'max:3'],
            'stops.*' => ['nullable', 'integer', 'exists:locations,id'],
        ];
    }

    public function getSanitized()
    {
        $data = $this->validated();
        unset($data['stops']);
        $data['currency_exchange_rate'] = Currency::find($this->get('currency_id'))->exchange_rate;
        return $data;
    }
}

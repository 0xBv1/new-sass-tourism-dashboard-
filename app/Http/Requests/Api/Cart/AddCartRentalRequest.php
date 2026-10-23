<?php

namespace App\Http\Requests\Api\Cart;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;

class AddCartRentalRequest extends FormRequest
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
            'stops' => ['nullable', 'array', 'max:3'],
            'stops.*' => ['nullable', 'integer', 'exists:locations,id'],
        ];
    }

    public function getSanitized()
    {
        return $this->validated();
    }
}

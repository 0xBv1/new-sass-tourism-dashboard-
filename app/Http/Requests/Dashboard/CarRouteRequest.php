<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CarRouteRequest extends FormRequest
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
            "pickup_location_id" => "Pickup Location",
            "destination_id" => "Destination",
        ];

        foreach ($this->get('prices', []) as $k=>$v) {
            $attributes['prices.' . $k . '.car_type'] = 'Car type ('. ($k+1) .')';
            $attributes['prices.' . $k . '.from'] = 'From ('. ($k+1) .')';
            $attributes['prices.' . $k . '.to'] = 'To ('. ($k+1) .')';
            $attributes['prices.' . $k . '.oneway_price'] = 'Oneway Price ('. ($k+1) .')';
            $attributes['prices.' . $k . '.rounded_price'] = 'Rounded Price ('. ($k+1) .')';
        }

        foreach ($this->get('stops', []) as $k=>$v) {
            $attributes['stops.' . $k . '.stop_location_id'] = 'Stop Location ('. ($k+1) .')';
            $attributes['stops.' . $k . '.price'] = 'Stop Location Price ('. ($k+1) .')';
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

        return [
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
            'destination_id' => ['required', 'integer', 'exists:locations,id'],
            'prices' => ['required', 'array', 'min:1'],
            'prices.*.id' => ['nullable', 'integer'],
            'prices.*.car_type' => ['required', 'string', 'min:1', 'max:255'],
            'prices.*.from' => ['required', 'integer', 'min:1'],
            'prices.*.to' => ['required', 'integer', 'min:1'],
            'prices.*.oneway_price' => ['required', 'numeric', 'min:1'],
            'prices.*.rounded_price' => ['required', 'numeric', 'min:1'],
            'stops' => ['nullable', 'array'],
            'stops.*.id' => ['nullable', 'integer'],
            'stops.*.stop_location_id' => ['required', 'integer', 'exists:locations,id'],
            'stops.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
    public function getSanitized(): array
    {
        return $this->only(['destination_id', 'pickup_location_id']);
    }
}

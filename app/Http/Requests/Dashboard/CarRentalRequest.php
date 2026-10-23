<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CarRentalRequest extends FormRequest
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
"pickup_location_id" => "PickupLocationId",
"destination_id" => "DestinationId",
"adults" => "Adults",
"children" => "Children",
"car_route_price" => "CarRoutePrice",
"car_type" => "CarType",
"oneway" => "Oneway",
"pickup_date" => "PickupDate",
"pickup_time" => "PickupTime",
"name" => "Name",
"email" => "Email",
"phone" => "Phone",
"nationality" => "Nationality",
"currency_id" => "CurrencyId",
"currency_exchange_rate" => "CurrencyExchangeRate",
];
        
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
            'pickup_location_id' => ['required'],
'destination_id' => ['required'],
'adults' => ['required'],
'children' => ['required'],
'car_route_price' => ['required'],
'car_type' => ['required'],
'oneway' => ['required'],
'pickup_date' => ['required'],
'pickup_time' => ['required'],
'name' => ['required'],
'email' => ['required'],
'phone' => ['required'],
'nationality' => ['required'],
'currency_id' => ['required'],
'currency_exchange_rate' => ['required'],

        ];
        
        return $rules;
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
     public function getSanitized(): array
     {
          return $this->validated();
     }
}

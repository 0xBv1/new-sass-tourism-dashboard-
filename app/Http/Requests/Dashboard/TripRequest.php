<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Trip;

class TripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'trip_type' => 'required|in:one_way,round_trip,special_discount',
            'departure_city_id' => 'required|exists:cities,id',
            'arrival_city_id' => 'required|exists:cities,id|different:departure_city_id',
            'seat_price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'additional_notes' => 'nullable|string|max:1000',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|in:Wi-Fi,Snacks,AC,TV,USB_Charging,Water,Blanket,Pillow',
            'enabled' => 'nullable'
        ];

        // Add conditional rules based on trip type
        if ($this->input('trip_type') === 'round_trip') {
            $rules['return_date'] = 'required|date|after:travel_date';
        } else {
            $rules['return_date'] = 'nullable|date|after:travel_date';
        }

        // Add date validation based on operation
        if ($this->isMethod('POST')) {
            // For creating new trips
            $rules['travel_date'] = 'required|date|after_or_equal:today';
        } else {
            // For updating existing trips
            $rules['travel_date'] = 'required|date';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'trip_type.required' => 'Please select a trip type.',
            'trip_type.in' => 'The selected trip type is invalid.',
            'departure_city_id.required' => 'Please select a departure city.',
            'departure_city_id.exists' => 'The selected departure city is invalid.',
            'arrival_city_id.required' => 'Please select an arrival city.',
            'arrival_city_id.exists' => 'The selected arrival city is invalid.',
            'arrival_city_id.different' => 'Departure and arrival cities must be different.',
            'travel_date.required' => 'Please select a travel date.',
            'travel_date.date' => 'Please enter a valid travel date.',
            'travel_date.after_or_equal' => 'Travel date must be today or a future date.',
            'return_date.required' => 'Return date is required for round trips.',
            'return_date.date' => 'Please enter a valid return date.',
            'return_date.after' => 'Return date must be after the travel date.',
            'seat_price.required' => 'Please enter a seat price.',
            'seat_price.numeric' => 'Seat price must be a number.',
            'seat_price.min' => 'Seat price must be at least 0.',
            'total_seats.required' => 'Please enter the total number of seats.',
            'total_seats.integer' => 'Total seats must be a whole number.',
            'total_seats.min' => 'Total seats must be at least 1.',
            'departure_time.required' => 'Please enter a departure time.',
            'departure_time.date_format' => 'Please enter a valid departure time (HH:MM format).',
            'arrival_time.required' => 'Please enter an arrival time.',
            'arrival_time.date_format' => 'Please enter a valid arrival time (HH:MM format).',
            'additional_notes.max' => 'Additional notes cannot exceed 1000 characters.',
            'amenities.array' => 'Amenities must be selected from the available options.',
            'amenities.*.in' => 'One or more selected amenities are invalid.',
            'enabled.boolean' => 'The enabled field must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'trip_type' => 'trip type',
            'departure_city_id' => 'departure city',
            'arrival_city_id' => 'arrival city',
            'travel_date' => 'travel date',
            'return_date' => 'return date',
            'seat_price' => 'seat price',
            'total_seats' => 'total seats',
            'departure_time' => 'departure time',
            'arrival_time' => 'arrival time',
            'additional_notes' => 'additional notes',
            'amenities' => 'amenities',
            'enabled' => 'enabled status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty string to null for optional fields
        $this->merge([
            'return_date' => $this->return_date ?: null,
            'additional_notes' => $this->additional_notes ?: null,
            'amenities' => $this->amenities ?: [],
            'enabled' => $this->has('enabled'),
        ]);
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}

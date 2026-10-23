<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TripSearchRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'trip_type' => 'required|in:one_way,round_trip',
            'departure_city_id' => 'required|exists:cities,id',
            'arrival_city_id' => 'required|exists:cities,id|different:departure_city_id',
            'passengers' => 'required|integer|min:1|max:50',
            'date_start' => 'required|date|after_or_equal:today',
        ];

        // Add date_end validation for round trips
        if ($this->input('trip_type') === 'round_trip') {
            $rules['date_end'] = 'nullable|date|after_or_equal:date_start';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'trip_type.required' => 'Trip type is required.',
            'trip_type.in' => 'Invalid trip type. Must be one_way or round_trip.',
            'departure_city_id.required' => 'Departure city is required.',
            'departure_city_id.exists' => 'Selected departure city does not exist.',
            'arrival_city_id.required' => 'Arrival city is required.',
            'arrival_city_id.exists' => 'Selected arrival city does not exist.',
            'arrival_city_id.different' => 'Departure and arrival cities must be different.',
            'date_start.required' => 'Start date is required.',
            'date_start.date' => 'Invalid start date format.',
            'date_start.after_or_equal' => 'Start date must be today or a future date.',
            'date_end.date' => 'Invalid end date format.',
            'date_end.after_or_equal' => 'End date must be after or equal to start date.',
            'passengers.required' => 'Number of passengers is required.',
            'passengers.integer' => 'Number of passengers must be a whole number.',
            'passengers.min' => 'Number of passengers must be at least 1.',
            'passengers.max' => 'Number of passengers cannot exceed 50.',
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
            'date_start' => 'start date',
            'date_end' => 'end date',
            'passengers' => 'number of passengers',
        ];
    }
}

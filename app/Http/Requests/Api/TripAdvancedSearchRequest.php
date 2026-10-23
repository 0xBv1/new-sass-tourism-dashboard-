<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TripAdvancedSearchRequest extends FormRequest
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
            'trip_type' => 'nullable|in:one_way,round_trip,special_discount',
            'departure_city_id' => 'nullable|exists:cities,id',
            'arrival_city_id' => 'nullable|exists:cities,id',
            'passengers' => 'nullable|integer|min:1|max:50',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'departure_time_from' => 'nullable|date_format:H:i',
            'departure_time_to' => 'nullable|date_format:H:i',
            'sort_by' => 'nullable|in:price,departure_time,travel_date,available_seats',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];

        // Validate that departure and arrival cities are different if both provided
        if ($this->has('departure_city_id') && $this->has('arrival_city_id')) {
            $rules['arrival_city_id'] .= '|different:departure_city_id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'trip_type.in' => 'Invalid trip type. Must be one_way, round_trip, or special_discount.',
            'departure_city_id.exists' => 'Selected departure city does not exist.',
            'arrival_city_id.exists' => 'Selected arrival city does not exist.',
            'arrival_city_id.different' => 'Departure and arrival cities must be different.',
            'date_from.required' => 'Start date is required.',
            'date_from.date' => 'Invalid start date format.',
            'date_from.after_or_equal' => 'Start date must be today or a future date.',
            'date_to.required' => 'End date is required.',
            'date_to.date' => 'Invalid end date format.',
            'date_to.after_or_equal' => 'End date must be after or equal to start date.',
            'passengers.integer' => 'Number of passengers must be a whole number.',
            'passengers.min' => 'Number of passengers must be at least 1.',
            'passengers.max' => 'Number of passengers cannot exceed 50.',
            'price_min.numeric' => 'Minimum price must be a number.',
            'price_min.min' => 'Minimum price cannot be negative.',
            'price_max.numeric' => 'Maximum price must be a number.',
            'price_max.min' => 'Maximum price cannot be negative.',
            'price_max.gte' => 'Maximum price must be greater than or equal to minimum price.',
            'departure_time_from.date_format' => 'Invalid departure time format. Use HH:MM format.',
            'departure_time_to.date_format' => 'Invalid departure time format. Use HH:MM format.',
            'sort_by.in' => 'Invalid sort field. Must be price, departure_time, travel_date, or available_seats.',
            'sort_order.in' => 'Invalid sort order. Must be asc or desc.',
            'per_page.integer' => 'Per page must be a whole number.',
            'per_page.min' => 'Per page must be at least 1.',
            'per_page.max' => 'Per page cannot exceed 100.',
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
            'date_from' => 'start date',
            'date_to' => 'end date',
            'passengers' => 'number of passengers',
            'price_min' => 'minimum price',
            'price_max' => 'maximum price',
            'departure_time_from' => 'departure time from',
            'departure_time_to' => 'departure time to',
            'sort_by' => 'sort by',
            'sort_order' => 'sort order',
            'per_page' => 'per page',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'sort_by' => $this->sort_by ?: 'travel_date',
            'sort_order' => $this->sort_order ?: 'asc',
            'per_page' => $this->per_page ?: 15,
        ]);
    }
} 
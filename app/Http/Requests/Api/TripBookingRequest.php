<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Trip;

class TripBookingRequest extends FormRequest
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
        return [
            'trip_id' => 'required|exists:trips,id',
            'number_of_passengers' => 'required|integer|min:1|max:20',
            'booking_date' => 'required|date|after_or_equal:today',
            'selected_seats' => 'nullable|array|min:1',
            'selected_seats.*' => 'integer|min:1',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'trip_id.required' => 'Trip ID is required',
            'trip_id.exists' => 'Selected trip does not exist',
            'number_of_passengers.required' => 'Number of passengers is required',
            'number_of_passengers.integer' => 'Number of passengers must be a number',
            'number_of_passengers.min' => 'Number of passengers must be at least 1',
            'number_of_passengers.max' => 'Number of passengers cannot exceed 20',
            'booking_date.required' => 'Booking date is required',
            'booking_date.date' => 'Booking date must be a valid date',
            'booking_date.after_or_equal' => 'Booking date must be today or a future date',
            'selected_seats.array' => 'Selected seats must be an array',
            'selected_seats.min' => 'At least one seat must be selected',
            'selected_seats.*.integer' => 'Seat numbers must be integers',
            'selected_seats.*.min' => 'Seat numbers must be at least 1',
            'contact_name.required' => 'Contact name is required',
            'contact_name.string' => 'Contact name must be a string',
            'contact_name.max' => 'Contact name cannot exceed 255 characters',
            'contact_phone.required' => 'Contact phone is required',
            'contact_phone.string' => 'Contact phone must be a string',
            'contact_phone.max' => 'Contact phone cannot exceed 20 characters',
            'contact_email.required' => 'Contact email is required',
            'contact_email.email' => 'Contact email must be a valid email address',
            'contact_email.max' => 'Contact email cannot exceed 255 characters',
            'special_requests.string' => 'Special requests must be a string',
            'special_requests.max' => 'Special requests cannot exceed 1000 characters',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'trip_id' => 'trip',
            'number_of_passengers' => 'number of passengers',
            'booking_date' => 'booking date',
            'selected_seats' => 'selected seats',
            'contact_name' => 'contact name',
            'contact_phone' => 'contact phone',
            'contact_email' => 'contact email',
            'special_requests' => 'special requests',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if trip has enough available seats
            $trip = Trip::find($this->trip_id);
            if ($trip) {
                $totalPassengers = $this->number_of_passengers;
                
                // If selected seats are provided, validate them
                if ($this->selected_seats && is_array($this->selected_seats)) {
                    $selectedSeatsCount = count($this->selected_seats);
                    
                    // Check if number of selected seats matches number of passengers
                    if ($selectedSeatsCount !== $totalPassengers) {
                        $validator->errors()->add('selected_seats', 'Number of selected seats must match the number of passengers.');
                        return;
                    }
                    
                    // Check if selected seats are available
                    if (!$trip->hasAvailableSeatsForNumbers($this->selected_seats)) {
                        $validator->errors()->add('selected_seats', 'One or more selected seats are not available.');
                        return;
                    }
                } else {
                    // Check if trip has enough available seats
                    if (!$trip->hasAvailableSeats($totalPassengers)) {
                        $validator->errors()->add('trip_id', 'Trip does not have enough available seats for the requested number of passengers.');
                    }
                }
            }
        });
    }
}

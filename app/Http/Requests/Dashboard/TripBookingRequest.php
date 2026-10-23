<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TripBooking;

class TripBookingRequest extends FormRequest
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
            'trip_id' => 'required|exists:trips,id',
            'client_id' => 'nullable|exists:clients,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'booking_date' => 'required|date',
            'number_of_passengers' => 'required|integer|min:1|max:50',
            'selected_seats' => 'nullable|array|min:1',
            'selected_seats.*' => 'integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', TripBooking::getStatuses()),
            'notes' => 'nullable|string|max:1000',
        ];

        // Add conditional rules based on operation
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For updating existing bookings
            $rules['booking_reference'] = 'required|string|max:50|unique:trip_bookings,booking_reference,' . $this->route('trip_booking');
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'trip_id.required' => 'Trip is required',
            'trip_id.exists' => 'Selected trip does not exist',
            'client_id.exists' => 'Selected client does not exist',
            'passenger_name.required' => 'Passenger name is required',
            'passenger_name.string' => 'Passenger name must be a string',
            'passenger_name.max' => 'Passenger name cannot exceed 255 characters',
            'passenger_email.required' => 'Passenger email is required',
            'passenger_email.email' => 'Passenger email must be a valid email address',
            'passenger_email.max' => 'Passenger email cannot exceed 255 characters',
            'passenger_phone.required' => 'Passenger phone is required',
            'passenger_phone.string' => 'Passenger phone must be a string',
            'passenger_phone.max' => 'Passenger phone cannot exceed 20 characters',
            'booking_date.required' => 'Booking date is required',
            'booking_date.date' => 'Booking date must be a valid date',
            'number_of_passengers.required' => 'Number of passengers is required',
            'number_of_passengers.integer' => 'Number of passengers must be a number',
            'number_of_passengers.min' => 'Number of passengers must be at least 1',
            'number_of_passengers.max' => 'Number of passengers cannot exceed 50',
            'total_price.required' => 'Total price is required',
            'total_price.numeric' => 'Total price must be a number',
            'total_price.min' => 'Total price cannot be negative',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
            'notes.string' => 'Notes must be a string',
            'notes.max' => 'Notes cannot exceed 1000 characters',
            'booking_reference.required' => 'Booking reference is required',
            'booking_reference.string' => 'Booking reference must be a string',
            'booking_reference.max' => 'Booking reference cannot exceed 50 characters',
            'booking_reference.unique' => 'Booking reference must be unique',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'trip_id' => 'trip',
            'client_id' => 'client',
            'passenger_name' => 'passenger name',
            'passenger_email' => 'passenger email',
            'passenger_phone' => 'passenger phone',
            'booking_date' => 'booking date',
            'number_of_passengers' => 'number of passengers',
            'total_price' => 'total price',
            'status' => 'status',
            'notes' => 'notes',
            'booking_reference' => 'booking reference',
        ];
    }
}

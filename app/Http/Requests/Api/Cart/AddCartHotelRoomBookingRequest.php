<?php

namespace App\Http\Requests\Api\Cart;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Room;

class AddCartHotelRoomBookingRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests_count' => 'required|integer|min:1|max:10',
            'extra_beds_count' => 'nullable|integer|min:0|max:5',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'hotel_id.required' => 'Hotel is required',
            'hotel_id.exists' => 'Selected hotel does not exist',
            'room_id.required' => 'Room is required',
            'room_id.exists' => 'Selected room does not exist',
            'start_date.after_or_equal' => 'Start date cannot be in the past',
            'end_date.after' => 'End date must be after start date',
            'guests_count.min' => 'At least 1 guest is required',
            'guests_count.max' => 'Maximum 10 guests allowed',
            'extra_beds_count.min' => 'Extra beds count cannot be negative',
            'extra_beds_count.max' => 'Maximum 5 extra beds allowed',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $roomId = $this->input('room_id');
            $guestsCount = $this->input('guests_count');
            $extraBedsCount = $this->input('extra_beds_count', 0);

            if ($roomId && $guestsCount) {
                $room = Room::find($roomId);
                if ($room) {
                    $totalCapacity = $room->getTotalCapacity();
                    if ($guestsCount > $totalCapacity) {
                        $validator->errors()->add('guests_count', "The number of guests exceeds the room's total capacity ({$totalCapacity} including extra beds).");
                    }
                }
            }

            // Validate extra beds
            if ($roomId && $extraBedsCount > 0) {
                $room = Room::find($roomId);
                if ($room && !$room->extra_bed_available) {
                    $validator->errors()->add('extra_beds_count', 'Extra beds are not available for this room.');
                } elseif ($room && $extraBedsCount > $room->max_extra_beds) {
                    $validator->errors()->add('extra_beds_count', "The maximum number of extra beds allowed is {$room->max_extra_beds}.");
                }
            }
        });
    }

    /**
     * Get sanitized data for processing.
     */
    public function getSanitized(): array
    {
        return $this->only([
            'hotel_id',
            'room_id',
            'name',
            'email',
            'phone',
            'nationality',
            'start_date',
            'end_date',
            'guests_count',
            'extra_beds_count',
        ]);
    }
} 
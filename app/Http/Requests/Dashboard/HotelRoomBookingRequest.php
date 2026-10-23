<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HotelRoomBooking;
use App\Models\Room;

class HotelRoomBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Client Data Rules
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string|max:255',

            // Booking Details Rules
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests_count' => 'required|integer|min:1',
            'status' => 'nullable|string|in:pending,confirmed',
            'extra_beds_count' => 'nullable|integer|min:0|max:5',
            'extra_beds_total_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $roomId = $this->input('room_id');
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');
            $guestsCount = $this->input('guests_count');
            $extraBedsCount = $this->input('extra_beds_count', 0);
            $bookingId = $this->route('hotel_room_booking'); // Get booking ID for updates

            if ($roomId && $startDate && $endDate) {
                $query = HotelRoomBooking::where('room_id', $roomId)
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->where(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<', $endDate)
                              ->where('end_date', '>', $startDate);
                        });
                    });
                
                // If updating, exclude the current booking from the check
                if ($bookingId) {
                    $query->where('id', '!=', $bookingId);
                }

                if ($query->exists()) {
                    $validator->errors()->add('room_id', 'This room is already booked for the selected date range.');
                }
            }

            if ($roomId && $guestsCount) {
                $room = Room::find($roomId);
                if ($room) {
                    $totalCapacity = $room->getTotalCapacity();
                    if ($guestsCount > $totalCapacity) {
                        $validator->errors()->add('guests_count', 'The number of guests exceeds the room\'s total capacity (' . $totalCapacity . ' including extra beds).');
                    }
                }
            }

            // Validate extra beds
            if ($roomId && $extraBedsCount > 0) {
                $room = Room::find($roomId);
                if ($room && !$room->extra_bed_available) {
                    $validator->errors()->add('extra_beds_count', 'Extra beds are not available for this room.');
                } elseif ($room && $extraBedsCount > $room->max_extra_beds) {
                    $validator->errors()->add('extra_beds_count', 'The maximum number of extra beds allowed is ' . $room->max_extra_beds . '.');
                }
            }
        });
    }
}

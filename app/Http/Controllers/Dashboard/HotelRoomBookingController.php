<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\HotelRoomBooking;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\HotelRoomBookingRequest;
use App\DataTables\HotelRoomBookingDataTable;
use App\Models\Hotel;
use App\Models\Room;

class HotelRoomBookingController extends Controller
{
    public function index(HotelRoomBookingDataTable $dataTable)
    {
        return $dataTable->render('dashboard.hotel_room_bookings.index');
    }

    public function create()
    {
        $hotels = Hotel::all();
        $selectedHotelId = request()->get('hotel_id') ?? $hotels->first()?->id;
        $rooms = $selectedHotelId ? Room::where('hotel_id', $selectedHotelId)->get() : collect();
        
        return view('dashboard.hotel_room_bookings.create', compact('hotels', 'rooms'));
    }
    
    public function store(HotelRoomBookingRequest $request)
    {
        $data = $request->validated();
        
        // Calculate total price
        $room = Room::find($data['room_id']);
        if ($room) {
            $startDate = \Carbon\Carbon::parse($data['start_date']);
            $endDate = \Carbon\Carbon::parse($data['end_date']);
            $nights = $startDate->diffInDays($endDate);
            $basePrice = $room->night_price * $nights;
            $extraBedsPrice = 0;
            
            // Calculate extra bed pricing if applicable
            if (isset($data['extra_beds_count']) && $data['extra_beds_count'] > 0 && $room->extra_bed_available) {
                $extraBedsPrice = $room->extra_bed_price * $data['extra_beds_count'] * $nights;
                $data['extra_beds_total_price'] = $extraBedsPrice;
            } else {
                $data['extra_beds_count'] = $data['extra_beds_count'] ?? 0;
                $data['extra_beds_total_price'] = 0;
            }
            
            // Calculate total price
            $data['total_price'] = $basePrice + $extraBedsPrice;
        }
        
        // For dashboard, save booking directly instead of using cart
        HotelRoomBooking::create($data);
        return redirect()->route('dashboard.hotel_room_bookings.index')
            ->with('success', 'Booking created successfully');
    }

    public function show(HotelRoomBooking $hotelRoomBooking)
    {
        // The variable name must match the route parameter name
        $booking = $hotelRoomBooking->load(['hotel', 'room']);
        return view('dashboard.hotel_room_bookings.show', compact('booking'));
    }

    public function edit(HotelRoomBooking $hotelRoomBooking)
    {
        $booking = $hotelRoomBooking; // Use the resolved model
        $hotels = Hotel::all();
        $rooms = Room::where('hotel_id', $booking->hotel_id)->get();
        
        // dd($booking); // Debugging line, remove in production
        $booking->start_date = \Carbon\Carbon::parse($booking->start_date)->format('Y/m/d');
        $booking->end_date = \Carbon\Carbon::parse($booking->end_date)->format('Y/m/d');

        return view('dashboard.hotel_room_bookings.edit', compact('booking', 'hotels', 'rooms'));
    }

    public function update(HotelRoomBookingRequest $request, HotelRoomBooking $hotelRoomBooking)
    {
        $data = $request->validated();
        
        // Calculate total price
        $room = Room::find($data['room_id']);
        if ($room) {
            $startDate = \Carbon\Carbon::parse($data['start_date']);
            $endDate = \Carbon\Carbon::parse($data['end_date']);
            $nights = $startDate->diffInDays($endDate);
            $basePrice = $room->night_price * $nights;
            $extraBedsPrice = 0;
            
            // Calculate extra bed pricing if applicable
            if (isset($data['extra_beds_count']) && $data['extra_beds_count'] > 0 && $room->extra_bed_available) {
                $extraBedsPrice = $room->extra_bed_price * $data['extra_beds_count'] * $nights;
                $data['extra_beds_total_price'] = $extraBedsPrice;
            } else {
                $data['extra_beds_count'] = $data['extra_beds_count'] ?? 0;
                $data['extra_beds_total_price'] = 0;
            }
            
            // Calculate total price
            $data['total_price'] = $basePrice + $extraBedsPrice;
        }
        
        $hotelRoomBooking->update($data);
        return redirect()->route('dashboard.hotel_room_bookings.index')->with('success', 'Booking updated successfully');
    }

    public function destroy(HotelRoomBooking $hotelRoomBooking)
    {
        $hotelRoomBooking->delete();
        return response()->json([
            'message' => 'Booking Deleted Successfully!'
        ]); 
    }

    public function getRoomsByHotel(Hotel $hotel)
    {
        // Return all necessary fields for price calculation
        $rooms = $hotel->rooms()->select(
            'id', 
            'name', 
            'night_price', 
            'extra_bed_available', 
            'extra_bed_price', 
            'max_extra_beds', 
            'max_capacity'
        )->get();
        return response()->json(['rooms' => $rooms]);
    }
}

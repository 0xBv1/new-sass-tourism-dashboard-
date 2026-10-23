<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripBooking;
use Illuminate\Http\Request;

class TripBookingController extends Controller
{
    /**
     * Get all trip bookings
     */
    public function index(Request $request)
    {
        $query = TripBooking::with(['trip', 'client']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        if ($request->has('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }

        $perPage = $request->get('per_page', 15);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bookings->items(),
            'message' => 'Trip bookings retrieved successfully',
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
            ]
        ]);
    }

    /**
     * Get trip booking details
     */
    public function show(TripBooking $tripBooking)
    {
        $tripBooking->load(['trip', 'client']);

        return response()->json([
            'success' => true,
            'data' => $tripBooking,
            'message' => 'Trip booking details retrieved successfully'
        ]);
    }
}

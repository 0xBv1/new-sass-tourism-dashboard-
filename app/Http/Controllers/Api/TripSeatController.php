<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripSeat;
use Illuminate\Http\Request;

class TripSeatController extends Controller
{
    /**
     * Get all seats for a trip
     */
    public function getSeats(Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip is not available'
            ], 404);
        }

        $seats = $trip->getAllSeats()->map(function ($seat) {
            return [
                'seat_number' => $seat->seat_number,
                'is_available' => $seat->is_available,
                'status' => $seat->status_label,
                'status_color' => $seat->status_color,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'trip_id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'total_seats' => $trip->total_seats,
                'available_seats' => $trip->available_seats,
                'seats' => $seats
            ]
        ]);
    }

    /**
     * Get available seats for a trip
     */
    public function getAvailableSeats(Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip is not available'
            ], 404);
        }

        $availableSeats = $trip->getAvailableSeats()->pluck('seat_number')->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'trip_id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'available_seats' => $availableSeats,
                'available_count' => count($availableSeats)
            ]
        ]);
    }

    /**
     * Get best available seats for a trip
     */
    public function getBestAvailableSeats(Request $request, Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip is not available'
            ], 404);
        }

        $request->validate([
            'count' => 'required|integer|min:1|max:' . $trip->total_seats
        ]);

        $count = $request->count;
        $bestSeats = TripSeat::getBestAvailableSeats($trip->id, $count);

        if (empty($bestSeats)) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough available seats'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'trip_id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'requested_count' => $count,
                'best_available_seats' => $bestSeats,
                'total_price' => $trip->calculateTotalPriceForSeats($bestSeats)
            ]
        ]);
    }

    /**
     * Get seat map for a trip (visual representation)
     */
    public function getSeatMap(Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip is not available'
            ], 404);
        }

        $seats = $trip->getAllSeats();
        
        // Create a seat map (assuming 4 seats per row)
        $seatsPerRow = 4;
        $totalRows = ceil($trip->total_seats / $seatsPerRow);
        
        $seatMap = [];
        for ($row = 1; $row <= $totalRows; $row++) {
            $rowSeats = [];
            for ($col = 1; $col <= $seatsPerRow; $col++) {
                $seatNumber = ($row - 1) * $seatsPerRow + $col;
                if ($seatNumber <= $trip->total_seats) {
                    $seat = $seats->where('seat_number', $seatNumber)->first();
                    $rowSeats[] = [
                        'seat_number' => $seatNumber,
                        'is_available' => $seat ? $seat->is_available : false,
                        'status' => $seat ? $seat->status_label : 'غير متاح',
                        'status_color' => $seat ? $seat->status_color : 'secondary',
                    ];
                } else {
                    $rowSeats[] = null; // Empty seat
                }
            }
            $seatMap[] = [
                'row' => $row,
                'seats' => $rowSeats
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'trip_id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'total_seats' => $trip->total_seats,
                'available_seats' => $trip->available_seats,
                'seats_per_row' => $seatsPerRow,
                'total_rows' => $totalRows,
                'seat_map' => $seatMap
            ]
        ]);
    }
}

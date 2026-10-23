<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\City;
use App\Http\Requests\Api\TripSearchRequest;
use App\Http\Requests\Api\TripAdvancedSearchRequest;
use App\Http\Requests\Api\TripBookingRequest;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Trips",
 *     description="Trip management endpoints"
 * )
 */
class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trips",
     *     summary="Get all available trips",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="trip_type",
     *         in="query",
     *         description="Filter by trip type",
     *         required=false,
     *         @OA\Schema(type="string", enum={"one_way", "round_trip", "special_discount"})
     *     ),
     *     @OA\Parameter(
     *         name="departure_city_id",
     *         in="query",
     *         description="Filter by departure city ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="arrival_city_id",
     *         in="query",
     *         description="Filter by arrival city ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter by travel date from",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter by travel date to",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="price_min",
     *         in="query",
     *         description="Filter by minimum price",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="price_max",
     *         in="query",
     *         description="Filter by maximum price",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
     *                     @OA\Property(property="trip_type", type="string", example="one_way"),
     *                     @OA\Property(property="trip_type_label", type="string", example="One Way"),
     *                     @OA\Property(property="from", type="string", example="Cairo"),
     *                     @OA\Property(property="to", type="string", example="Alexandria"),
     *                     @OA\Property(property="travel_date", type="string", format="date", example="2025-08-20"),
     *                     @OA\Property(property="return_date", type="string", format="date", nullable=true),
     *                     @OA\Property(property="departure_time", type="string", example="08:00"),
     *                     @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                     @OA\Property(property="price", type="number", format="float", example=150.00),
     *                     @OA\Property(property="amenities", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="additional_notes", type="string", nullable=true),
     *                     @OA\Property(property="total_seats", type="integer", example=50),
     *                     @OA\Property(property="available_seats", type="integer", example=35),
     *                     @OA\Property(property="occupancy_rate", type="number", format="float", example=70.0),
     *                     @OA\Property(property="occupancy_status", type="string", example="available", enum={"available", "limited", "full"})
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=150),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="to", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Trip::available()
            ->with(['departureCity', 'arrivalCity'])
            ->orderBy('travel_date')
            ->orderBy('departure_time');

        // Filter by trip type
        if ($request->has('trip_type')) {
            $query->where('trip_type', $request->trip_type);
        }

        // Filter by departure city
        if ($request->has('departure_city_id')) {
            $query->where('departure_city_id', $request->departure_city_id);
        }

        // Filter by arrival city
        if ($request->has('arrival_city_id')) {
            $query->where('arrival_city_id', $request->arrival_city_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('travel_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('travel_date', '<=', $request->date_to);
        }

        // Filter by price range
        if ($request->has('price_min')) {
            $query->where('seat_price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('seat_price', '<=', $request->price_max);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $trips = $query->paginate($perPage);

        $formattedTrips = $trips->getCollection()->map(function ($trip) {
            return [
                'id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'trip_type' => $trip->trip_type,
                'trip_type_label' => $trip->trip_type_label,
                'from' => $trip->departure_city_name,
                'to' => $trip->arrival_city_name,
                'travel_date' => $trip->travel_date->format('Y-m-d'),
                'return_date' => $trip->return_date ? $trip->return_date->format('Y-m-d') : null,
                'departure_time' => $trip->formatted_departure_time,
                'arrival_time' => $trip->formatted_arrival_time,
                'price' => (float) $trip->seat_price,
                'amenities' => $trip->amenities ?? [],
                'additional_notes' => $trip->additional_notes,
                'total_seats' => $trip->total_seats,
                'available_seats' => $trip->available_seats,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedTrips,
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
                'from' => $trips->firstItem(),
                'to' => $trips->lastItem(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips/search",
     *     summary="Search for trips with basic criteria",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_type", "departure_city_id", "arrival_city_id", "passengers", "date_start"},
     *             @OA\Property(property="trip_type", type="string", enum={"one_way", "round_trip"}, example="one_way"),
     *             @OA\Property(property="departure_city_id", type="integer", example=1),
     *             @OA\Property(property="arrival_city_id", type="integer", example=2),
     *             @OA\Property(property="passengers", type="integer", minimum=1, example=2),
     *             @OA\Property(property="date_start", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="date_end", type="string", format="date", example="2025-08-25")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="from", type="string", example="Cairo"),
     *                     @OA\Property(property="to", type="string", example="Alexandria"),
     *                     @OA\Property(property="departure_time", type="string", example="08:00"),
     *                     @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                     @OA\Property(property="date", type="string", format="date", example="2025-08-20"),
     *                     @OA\Property(property="price", type="number", format="float", example=150.00),
     *                     @OA\Property(property="amenities", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="notes", type="string"),
     *                     @OA\Property(property="trip_type", type="string", example="one_way"),
     *                     @OA\Property(property="additional_notes", type="string", nullable=true),
     *                     @OA\Property(property="total_seats", type="integer", example=50),
     *                     @OA\Property(property="available_seats", type="integer", example=35),
     *                     @OA\Property(property="occupancy_rate", type="number", format="float", example=70.0),
     *                     @OA\Property(property="occupancy_status", type="string", example="available", enum={"available", "limited", "full"})
     *                 )
     *             ),
     *             @OA\Property(property="search_criteria", type="object"),
     *             @OA\Property(property="total_trips_found", type="integer", example=5),
     *             @OA\Property(property="pagination", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function search(TripSearchRequest $request)
    {
        $data = $request->validated();

        $query = Trip::available()
            ->where('departure_city_id', $data['departure_city_id'])
            ->where('arrival_city_id', $data['arrival_city_id'])
            ->where('available_seats', '>=', $data['passengers']);

        // Handle date filtering based on trip type
        if ($data['trip_type'] === 'one_way') {
            // For one-way trips, search from date_start for 5 days
            $dateStart = \Carbon\Carbon::parse($data['date_start']);
            $dateEnd = $dateStart->copy()->addDays(4); // 5 days total (start date + 4 more days)
            
            $query->whereBetween('travel_date', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')]);
        } else {
            // For round trips, use date_start and date_end if provided
            if (isset($data['date_end'])) {
                $query->whereBetween('travel_date', [$data['date_start'], $data['date_end']]);
            } else {
                // If no date_end provided for round trip, search from date_start for 5 days
                $dateStart = \Carbon\Carbon::parse($data['date_start']);
                $dateEnd = $dateStart->copy()->addDays(4);
                
                $query->whereBetween('travel_date', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')]);
            }
        }

        // Filter by trip type
        if ($data['trip_type'] === 'one_way') {
            $query->whereIn('trip_type', ['one_way', 'special_discount']);
        } else {
            $query->where('trip_type', 'round_trip');
        }

        // Paginate results (like GoBus - show 5 days of results)
        $perPage = 15; // Default per page
        $trips = $query->with(['departureCity', 'arrivalCity'])
                      ->orderBy('travel_date')
                      ->orderBy('departure_time')
                      ->paginate($perPage);

        $formattedTrips = $trips->getCollection()->map(function ($trip) use ($data) {
            return [
                'id' => $trip->id,
                'from' => $trip->departure_city_name,
                'to' => $trip->arrival_city_name,
                'departure_time' => $trip->formatted_departure_time,
                'arrival_time' => $trip->formatted_arrival_time,
                'date' => $trip->travel_date->format('Y-m-d'),
                'price' => (float) $trip->seat_price,
                'amenities' => $trip->amenities ?? [],
                'notes' => $this->generateSearchNotes($trip, $data),
                'trip_type' => $trip->trip_type,
                'additional_notes' => $trip->additional_notes,
                'total_seats' => $trip->total_seats,
                'available_seats' => $trip->available_seats,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
            ];
        });

        // Prepare search criteria for response
        $searchCriteria = [
            'trip_type' => $data['trip_type'],
            'departure_city_id' => $data['departure_city_id'],
            'arrival_city_id' => $data['arrival_city_id'],
            'passengers' => $data['passengers'],
            'date_start' => $data['date_start']
        ];

        // Add date_end to search criteria if provided for round trips
        if ($data['trip_type'] === 'round_trip' && isset($data['date_end'])) {
            $searchCriteria['date_end'] = $data['date_end'];
        }

        return response()->json([
            'success' => true,
            'data' => $formattedTrips,
            'search_criteria' => $searchCriteria,
            'total_trips_found' => $trips->total(),
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'last_page' => $trips->lastPage(),
                'per_page' => $trips->perPage(),
                'total' => $trips->total(),
                'from' => $trips->firstItem(),
                'to' => $trips->lastItem(),
            ]
        ]);
    }

   
    /**
     * @OA\Get(
     *     path="/api/trips/{trip}",
     *     summary="Get trip details by ID",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="Trip ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="trip",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
     *                 @OA\Property(property="trip_type", type="string", example="one_way"),
     *                 @OA\Property(property="trip_type_label", type="string", example="One Way"),
     *                 @OA\Property(property="from", type="string", example="Cairo"),
     *                 @OA\Property(property="to", type="string", example="Alexandria"),
     *                 @OA\Property(property="travel_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="return_date", type="string", format="date", nullable=true),
     *                 @OA\Property(property="departure_time", type="string", example="08:00"),
     *                 @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                 @OA\Property(property="price", type="number", format="float", example=150.00),
     *                 @OA\Property(property="amenities", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="additional_notes", type="string", nullable=true),
     *                 @OA\Property(property="total_seats", type="integer", example=50),
     *                 @OA\Property(property="available_seats", type="integer", example=35),
     *                 @OA\Property(property="occupancy_rate", type="number", format="float", example=70.0),
     *                 @OA\Property(property="occupancy_status", type="string", example="available", enum={"available", "limited", "full"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found"
     *     )
     * )
     */
    public function show(Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not available'
            ], 404);
        }

        $trip->load(['departureCity', 'arrivalCity']);

        return response()->json([
            'success' => true,
            'trip' => [
                'id' => $trip->id,
                'trip_name' => $trip->trip_name,
                'trip_type' => $trip->trip_type,
                'trip_type_label' => $trip->trip_type_label,
                'from' => $trip->departure_city_name,
                'to' => $trip->arrival_city_name,
                'travel_date' => $trip->travel_date->format('Y-m-d'),
                'return_date' => $trip->return_date ? $trip->return_date->format('Y-m-d') : null,
                'departure_time' => $trip->formatted_departure_time,
                'arrival_time' => $trip->formatted_arrival_time,
                'price' => (float) $trip->seat_price,
                'amenities' => $trip->amenities ?? [],
                'additional_notes' => $trip->additional_notes,
                'total_seats' => $trip->total_seats,
                'available_seats' => $trip->available_seats,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/trips/{trip}/seats",
     *     summary="Get available seats for a trip",
     *     tags={"Trips"},
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="Trip ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="seat_number", type="integer", example=1),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="status_label", type="string", example="متاح"),
     *                     @OA\Property(property="status_color", type="string", example="success")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found"
     *     )
     * )
     */
    public function seats(Trip $trip)
    {
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not available'
            ], 404);
        }

        $seats = \App\Models\TripSeat::getSeatsStatusForTrip($trip->id);

        return response()->json([
            'success' => true,
            'data' => $seats->map(function ($seat) {
                return [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                    'is_available' => $seat->is_available,
                    'status_label' => $seat->status_label,
                    'status_color' => $seat->status_color,
                ];
            })
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips/book",
     *     summary="Book a trip",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_id", "contact_name", "contact_email", "contact_phone", "number_of_passengers", "booking_date"},
     *             @OA\Property(property="trip_id", type="integer", example=1),
     *             @OA\Property(property="contact_name", type="string", example="John Doe"),
     *             @OA\Property(property="contact_email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="contact_phone", type="string", example="+1234567890"),
     *             @OA\Property(property="number_of_passengers", type="integer", minimum=1, example=2),
     *             @OA\Property(property="selected_seats", type="array", @OA\Items(type="string"), nullable=true),
     *             @OA\Property(property="booking_date", type="string", format="date", example="2025-08-20"),
     *             @OA\Property(property="special_requests", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Trip booked successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="booking_reference", type="string", example="TRP-2025-001"),
     *                 @OA\Property(property="selected_seats", type="array", @OA\Items(type="string"), nullable=true),
     *                 @OA\Property(property="total_price", type="number", format="float", example=300.00),
     *                 @OA\Property(property="number_of_passengers", type="integer", example=2),
     *                 @OA\Property(property="booking_date", type="string", format="date", example="2025-08-20"),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Booking failed"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function book(TripBookingRequest $request)
    {
        $data = $request->validated();
        $trip = Trip::findOrFail($data['trip_id']);

        // Check if trip is available
        if (!$trip->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Trip is not available'
            ], 400);
        }

        $totalPassengers = $data['number_of_passengers'];
        $selectedSeats = $data['selected_seats'] ?? null;

        // Calculate total price
        if ($selectedSeats) {
            $totalPrice = $trip->calculateTotalPriceForSeats($selectedSeats);
        } else {
            $totalPrice = $trip->calculateTotalPrice($totalPassengers);
        }

        // Create booking
        $bookingData = [
            'trip_id' => $trip->id,
            'passenger_name' => $data['contact_name'],
            'passenger_email' => $data['contact_email'],
            'passenger_phone' => $data['contact_phone'],
            'booking_date' => $data['booking_date'],
            'number_of_passengers' => $totalPassengers,
            'selected_seats' => $selectedSeats,
            'total_price' => $totalPrice,
            'notes' => $data['special_requests'] ?? null,
            'status' => \App\Models\TripBooking::STATUS_PENDING,
            'booking_reference' => \App\Models\TripBooking::generateBookingReference(),
        ];

        // Add client_id if user is authenticated
        if (auth('client')->check()) {
            $bookingData['client_id'] = auth('client')->id();
        }

        $booking = \App\Models\TripBooking::create($bookingData);

        // Book the selected seats if provided
        if ($selectedSeats) {
            \App\Models\TripSeat::bookSeats($trip->id, $selectedSeats, $booking->id);
        }

        // Update available seats
        $trip->decrement('available_seats', $totalPassengers);

        return response()->json([
            'success' => true,
            'message' => 'Trip booked successfully',
            'data' => [
                'booking_reference' => $booking->booking_reference,
                'selected_seats' => $selectedSeats,
                'total_price' => $totalPrice,
                'number_of_passengers' => $totalPassengers,
                'booking_date' => $booking->booking_date,
                'status' => $booking->status
            ]
        ]);
    }

    private function generateNotes(Trip $trip, array $searchData): string
    {
        $notes = [];

        if ($trip->trip_type === 'round_trip') {
            $notes[] = 'Round trip booking';
        }

        if ($trip->trip_type === 'special_discount') {
            $notes[] = 'Special discount applied';
        }

        if ($searchData['passengers'] > 1) {
            $notes[] = "Group booking for {$searchData['passengers']} passengers";
        }

        // Add date range information if applicable
        if (isset($searchData['date_from']) && isset($searchData['date_to'])) {
            $fromDate = \Carbon\Carbon::parse($searchData['date_from'])->format('M d, Y');
            $toDate = \Carbon\Carbon::parse($searchData['date_to'])->format('M d, Y');
            $notes[] = "Available in date range: {$fromDate} to {$toDate}";
        }

        if ($trip->amenities && count($trip->amenities) > 0) {
            $amenitiesList = implode(', ', $trip->amenities);
            $notes[] = "Amenities: {$amenitiesList}";
        }

        if ($trip->additional_notes) {
            $notes[] = $trip->additional_notes;
        }

        return implode('. ', $notes);
    }

    private function generateSearchNotes(Trip $trip, array $searchData): string
    {
        $notes = [];

        if ($trip->trip_type === 'round_trip') {
            $notes[] = 'Round trip booking';
        }

        if ($trip->trip_type === 'special_discount') {
            $notes[] = 'Special discount applied';
        }

        if (isset($searchData['passengers']) && $searchData['passengers'] > 1) {
            $notes[] = "Group booking for {$searchData['passengers']} passengers";
        }

        // Add date range information
        $dateStart = \Carbon\Carbon::parse($searchData['date_start'])->format('M d, Y');
        $dateEnd = \Carbon\Carbon::parse($searchData['date_end'] ?? $searchData['date_start'])->format('M d, Y');
        $notes[] = "Available in date range: {$dateStart} to {$dateEnd}";

        // Add price range information if applicable
        if (isset($searchData['price_min']) || isset($searchData['price_max'])) {
            $priceInfo = "Price range: ";
            if (isset($searchData['price_min'])) {
                $priceInfo .= "from " . number_format($searchData['price_min'], 2) . " EGP";
            }
            if (isset($searchData['price_max'])) {
                $priceInfo .= " to " . number_format($searchData['price_max'], 2) . " EGP";
            }
            $notes[] = $priceInfo;
        }

        if ($trip->amenities && count($trip->amenities) > 0) {
            $amenitiesList = implode(', ', $trip->amenities);
            $notes[] = "Amenities: {$amenitiesList}";
        }

        if ($trip->additional_notes) {
            $notes[] = $trip->additional_notes;
        }

        return implode('. ', $notes);
    }

    private function generateAdvancedNotes(Trip $trip, array $searchData): string
    {
        $notes = [];

        if ($trip->trip_type === 'round_trip') {
            $notes[] = 'Round trip booking';
        }

        if ($trip->trip_type === 'special_discount') {
            $notes[] = 'Special discount applied';
        }

        if (isset($searchData['passengers']) && $searchData['passengers'] > 1) {
            $notes[] = "Group booking for {$searchData['passengers']} passengers";
        }

        // Add date range information
        $fromDate = \Carbon\Carbon::parse($searchData['date_from'])->format('M d, Y');
        $toDate = \Carbon\Carbon::parse($searchData['date_to'])->format('M d, Y');
        $notes[] = "Available in date range: {$fromDate} to {$toDate}";

        // Add price range information if applicable
        if (isset($searchData['price_min']) || isset($searchData['price_max'])) {
            $priceInfo = "Price range: ";
            if (isset($searchData['price_min'])) {
                $priceInfo .= "from " . number_format($searchData['price_min'], 2) . " EGP";
            }
            if (isset($searchData['price_max'])) {
                $priceInfo .= " to " . number_format($searchData['price_max'], 2) . " EGP";
            }
            $notes[] = $priceInfo;
        }

        if ($trip->amenities && count($trip->amenities) > 0) {
            $amenitiesList = implode(', ', $trip->amenities);
            $notes[] = "Amenities: {$amenitiesList}";
        }

        if ($trip->additional_notes) {
            $notes[] = $trip->additional_notes;
        }

        return implode('. ', $notes);
    }
}

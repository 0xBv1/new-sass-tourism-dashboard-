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
 *     description="Trip management and search endpoints"
 * )
 */
class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/trips",
     *     summary="Get all available trips",
     *     description="Retrieve a paginated list of all available trips with optional filtering",
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
     *         description="Filter trips from this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter trips until this date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="price_min",
     *         in="query",
     *         description="Minimum price filter",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="price_max",
     *         in="query",
     *         description="Maximum price filter",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
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
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
     *                 @OA\Property(property="trip_type", type="string", example="one_way"),
     *                 @OA\Property(property="from", type="string", example="Cairo"),
     *                 @OA\Property(property="to", type="string", example="Alexandria"),
     *                 @OA\Property(property="travel_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="departure_time", type="string", example="08:00"),
     *                 @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                 @OA\Property(property="price", type="number", example=150.00),
     *                 @OA\Property(property="available_seats", type="integer", example=25),
     *                 @OA\Property(property="seats_info", type="object",
     *                     @OA\Property(property="total_seats", type="integer", example=30),
     *                     @OA\Property(property="available_seats", type="integer", example=25),
     *                     @OA\Property(property="booked_seats", type="integer", example=5),
     *                     @OA\Property(property="occupancy_rate", type="number", example=16.67),
     *                     @OA\Property(property="occupancy_status", type="string", example="Available"),
     *                     @OA\Property(property="available_seat_numbers", type="array", @OA\Items(type="integer"), example={1,2,3,4,5}),
     *                     @OA\Property(property="booked_seat_numbers", type="array", @OA\Items(type="integer"), example={6,7,8,9,10})
     *                 )
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=75)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Trip::available()
            ->with(['departureCity', 'arrivalCity', 'seats'])
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
                'available_seats' => $trip->available_seats,
                'total_seats' => $trip->total_seats,
                'additional_notes' => $trip->additional_notes,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
                'seats_info' => [
                    'total_seats' => $trip->total_seats,
                    'available_seats' => $trip->available_seats,
                    'booked_seats' => $trip->booked_seats,
                    'occupancy_rate' => $trip->occupancy_rate,
                    'occupancy_status' => $trip->occupancy_status,
                    'seat_map' => $this->generateSeatMap($trip),
                    'available_seat_numbers' => $trip->seats()->available()->pluck('seat_number')->toArray(),
                    'booked_seat_numbers' => $trip->seats()->booked()->pluck('seat_number')->toArray(),
                ],
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
     *     summary="Search trips with basic criteria",
     *     description="Search for trips using basic criteria including single date or date range",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_type", "departure_city_id", "arrival_city_id", "passengers"},
     *             @OA\Property(property="trip_type", type="string", enum={"one_way", "round_trip", "special_discount"}, example="one_way"),
     *             @OA\Property(property="departure_city_id", type="integer", example=1),
     *             @OA\Property(property="arrival_city_id", type="integer", example=2),
     *             @OA\Property(property="passengers", type="integer", minimum=1, maximum=50, example=2),
     *             @OA\Property(property="travel_date", type="string", format="date", description="Single date search (YYYY-MM-DD)"),
     *             @OA\Property(property="date_from", type="string", format="date", description="Start date for range search (YYYY-MM-DD)"),
     *             @OA\Property(property="date_to", type="string", format="date", description="End date for range search (YYYY-MM-DD)"),
     *             @OA\Property(property="return_date", type="string", format="date", description="Return date for round trips")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="trips", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="from", type="string", example="Cairo"),
     *                 @OA\Property(property="to", type="string", example="Alexandria"),
     *                 @OA\Property(property="departure_time", type="string", example="08:00"),
     *                 @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                 @OA\Property(property="date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="price", type="number", example=150.00),
     *                 @OA\Property(property="available_seats", type="integer", example=25)
     *             )),
     *             @OA\Property(property="search_criteria", type="object",
     *                 @OA\Property(property="trip_type", type="string", example="one_way"),
     *                 @OA\Property(property="departure_city_id", type="integer", example=1),
     *                 @OA\Property(property="arrival_city_id", type="integer", example=2),
     *                 @OA\Property(property="passengers", type="integer", example=2)
     *             ),
     *             @OA\Property(property="total_trips_found", type="integer", example=5),
     *             @OA\Property(property="date_range_info", type="object",
     *                 @OA\Property(property="search_type", type="string", example="date_range"),
     *                 @OA\Property(property="from_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="to_date", type="string", example="2024-01-20"),
     *                 @OA\Property(property="total_days", type="integer", example=6)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
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

        // Handle date filtering - support both single date and date range
        if (isset($data['travel_date'])) {
            // Single date search
            $query->whereDate('travel_date', $data['travel_date']);
        } elseif (isset($data['date_from']) && isset($data['date_to'])) {
            // Date range search
            $query->whereBetween('travel_date', [$data['date_from'], $data['date_to']]);
        }

        // Filter by trip type
        if ($data['trip_type'] === 'one_way') {
            $query->whereIn('trip_type', ['one_way', 'special_discount']);
        } else {
            $query->where('trip_type', 'round_trip');
        }

        $trips = $query->with(['departureCity', 'arrivalCity', 'seats'])
                      ->orderBy('travel_date')
                      ->orderBy('departure_time')
                      ->get();

        $formattedTrips = $trips->map(function ($trip) use ($data) {
            return [
                'id' => $trip->id,
                'from' => $trip->departure_city_name,
                'to' => $trip->arrival_city_name,
                'departure_time' => $trip->formatted_departure_time,
                'arrival_time' => $trip->formatted_arrival_time,
                'date' => $trip->travel_date->format('Y-m-d'),
                'price' => (float) $trip->seat_price,
                'amenities' => $trip->amenities ?? [],
                'available_seats' => $trip->available_seats,
                'total_seats' => $trip->total_seats,
                'notes' => $this->generateNotes($trip, $data),
                'trip_type' => $trip->trip_type,
                'additional_notes' => $trip->additional_notes,
                'seats_info' => [
                    'total_seats' => $trip->total_seats,
                    'available_seats' => $trip->available_seats,
                    'booked_seats' => $trip->booked_seats,
                    'occupancy_rate' => $trip->occupancy_rate,
                    'occupancy_status' => $trip->occupancy_status,
                    'available_seat_numbers' => $trip->seats()->available()->pluck('seat_number')->toArray(),
                    'booked_seat_numbers' => $trip->seats()->booked()->pluck('seat_number')->toArray(),
                ],
            ];
        });

        // Prepare search criteria for response
        $searchCriteria = [
            'trip_type' => $data['trip_type'],
            'departure_city_id' => $data['departure_city_id'],
            'arrival_city_id' => $data['arrival_city_id'],
            'passengers' => $data['passengers']
        ];

        // Add date information to search criteria
        if (isset($data['travel_date'])) {
            $searchCriteria['travel_date'] = $data['travel_date'];
        } elseif (isset($data['date_from']) && isset($data['date_to'])) {
            $searchCriteria['date_from'] = $data['date_from'];
            $searchCriteria['date_to'] = $data['date_to'];
        }

        if (isset($data['return_date'])) {
            $searchCriteria['return_date'] = $data['return_date'];
        }

        return response()->json([
            'success' => true,
            'trips' => $formattedTrips,
            'search_criteria' => $searchCriteria,
            'total_trips_found' => $formattedTrips->count(),
            'date_range_info' => isset($data['date_from']) ? [
                'search_type' => 'date_range',
                'from_date' => $data['date_from'],
                'to_date' => $data['date_to'],
                'total_days' => \Carbon\Carbon::parse($data['date_from'])->diffInDays($data['date_to']) + 1
            ] : [
                'search_type' => 'single_date',
                'date' => $data['travel_date'] ?? null
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips/advanced-search",
     *     summary="Advanced trip search with multiple filters",
     *     description="Search for trips using advanced criteria including date range, price range, time filters, and sorting",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date_from", "date_to"},
     *             @OA\Property(property="date_from", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="date_to", type="string", format="date", example="2024-01-20"),
     *             @OA\Property(property="trip_type", type="string", enum={"one_way", "round_trip", "special_discount"}),
     *             @OA\Property(property="departure_city_id", type="integer"),
     *             @OA\Property(property="arrival_city_id", type="integer"),
     *             @OA\Property(property="passengers", type="integer", minimum=1, maximum=50),
     *             @OA\Property(property="price_min", type="number", format="float", minimum=0),
     *             @OA\Property(property="price_max", type="number", format="float", minimum=0),
     *             @OA\Property(property="departure_time_from", type="string", format="time", example="08:00"),
     *             @OA\Property(property="departure_time_to", type="string", format="time", example="18:00"),
     *             @OA\Property(property="sort_by", type="string", enum={"price", "departure_time", "travel_date", "available_seats"}, default="travel_date"),
     *             @OA\Property(property="sort_order", type="string", enum={"asc", "desc"}, default="asc"),
     *             @OA\Property(property="per_page", type="integer", minimum=1, maximum=100, default=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
     *                 @OA\Property(property="trip_type", type="string", example="one_way"),
     *                 @OA\Property(property="from", type="string", example="Cairo"),
     *                 @OA\Property(property="to", type="string", example="Alexandria"),
     *                 @OA\Property(property="travel_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="departure_time", type="string", example="08:00"),
     *                 @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                 @OA\Property(property="price", type="number", example=150.00),
     *                 @OA\Property(property="available_seats", type="integer", example=25),
     *                 @OA\Property(property="seats_info", type="object",
     *                     @OA\Property(property="total_seats", type="integer", example=30),
     *                     @OA\Property(property="available_seats", type="integer", example=25),
     *                     @OA\Property(property="booked_seats", type="integer", example=5),
     *                     @OA\Property(property="occupancy_rate", type="number", example=16.67),
     *                     @OA\Property(property="occupancy_status", type="string", example="Available"),
     *                     @OA\Property(property="available_seat_numbers", type="array", @OA\Items(type="integer"), example={1,2,3,4,5}),
     *                     @OA\Property(property="booked_seat_numbers", type="array", @OA\Items(type="integer"), example={6,7,8,9,10})
     *                 )
     *             )),
     *             @OA\Property(property="search_criteria", type="object"),
     *             @OA\Property(property="total_trips_found", type="integer", example=25),
     *             @OA\Property(property="date_range_info", type="object",
     *                 @OA\Property(property="search_type", type="string", example="advanced_date_range"),
     *                 @OA\Property(property="from_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="to_date", type="string", example="2024-01-20"),
     *                 @OA\Property(property="total_days", type="integer", example=6)
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=75)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function advancedSearch(TripAdvancedSearchRequest $request)
    {
        $data = $request->validated();

        $query = Trip::available();

        // Filter by departure city
        if (isset($data['departure_city_id'])) {
            $query->where('departure_city_id', $data['departure_city_id']);
        }

        // Filter by arrival city
        if (isset($data['arrival_city_id'])) {
            $query->where('arrival_city_id', $data['arrival_city_id']);
        }

        // Filter by date range (required)
        $query->whereBetween('travel_date', [$data['date_from'], $data['date_to']]);

        // Filter by passengers
        if (isset($data['passengers'])) {
            $query->where('available_seats', '>=', $data['passengers']);
        }

        // Filter by trip type
        if (isset($data['trip_type'])) {
            if ($data['trip_type'] === 'one_way') {
                $query->whereIn('trip_type', ['one_way', 'special_discount']);
            } else {
                $query->where('trip_type', $data['trip_type']);
            }
        }

        // Filter by price range
        if (isset($data['price_min'])) {
            $query->where('seat_price', '>=', $data['price_min']);
        }

        if (isset($data['price_max'])) {
            $query->where('seat_price', '<=', $data['price_max']);
        }

        // Filter by departure time range
        if (isset($data['departure_time_from'])) {
            $query->whereTime('departure_time', '>=', $data['departure_time_from']);
        }

        if (isset($data['departure_time_to'])) {
            $query->whereTime('departure_time', '<=', $data['departure_time_to']);
        }

        // Apply sorting
        $sortBy = $data['sort_by'];
        $sortOrder = $data['sort_order'];

        switch ($sortBy) {
            case 'price':
                $query->orderBy('seat_price', $sortOrder);
                break;
            case 'departure_time':
                $query->orderBy('departure_time', $sortOrder);
                break;
            case 'available_seats':
                $query->orderBy('available_seats', $sortOrder);
                break;
            default:
                $query->orderBy('travel_date', $sortOrder)
                      ->orderBy('departure_time', 'asc');
        }

        // Paginate results
        $perPage = $data['per_page'];
        $trips = $query->with(['departureCity', 'arrivalCity', 'seats'])->paginate($perPage);

        $formattedTrips = $trips->getCollection()->map(function ($trip) use ($data) {
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
                'available_seats' => $trip->available_seats,
                'total_seats' => $trip->total_seats,
                'additional_notes' => $trip->additional_notes,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
                'notes' => $this->generateAdvancedNotes($trip, $data),
                'seats_info' => [
                    'total_seats' => $trip->total_seats,
                    'available_seats' => $trip->available_seats,
                    'booked_seats' => $trip->booked_seats,
                    'occupancy_rate' => $trip->occupancy_rate,
                    'occupancy_status' => $trip->occupancy_status,
                    'available_seat_numbers' => $trip->seats()->available()->pluck('seat_number')->toArray(),
                    'booked_seat_numbers' => $trip->seats()->booked()->pluck('seat_number')->toArray(),
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedTrips,
            'search_criteria' => $data,
            'total_trips_found' => $trips->total(),
            'date_range_info' => [
                'search_type' => 'advanced_date_range',
                'from_date' => $data['date_from'],
                'to_date' => $data['date_to'],
                'total_days' => \Carbon\Carbon::parse($data['date_from'])->diffInDays($data['date_to']) + 1
            ],
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
     *     summary="Get trip details",
     *     description="Retrieve detailed information about a specific trip",
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
     *             @OA\Property(property="trip", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="Cairo to Alexandria"),
     *                 @OA\Property(property="trip_type", type="string", example="one_way"),
     *                 @OA\Property(property="from", type="string", example="Cairo"),
     *                 @OA\Property(property="to", type="string", example="Alexandria"),
     *                 @OA\Property(property="travel_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="departure_time", type="string", example="08:00"),
     *                 @OA\Property(property="arrival_time", type="string", example="10:00"),
     *                 @OA\Property(property="price", type="number", example=150.00),
     *                 @OA\Property(property="available_seats", type="integer", example=25),
     *                 @OA\Property(property="seats_info", type="object",
     *                     @OA\Property(property="total_seats", type="integer", example=30),
     *                     @OA\Property(property="available_seats", type="integer", example=25),
     *                     @OA\Property(property="booked_seats", type="integer", example=5),
     *                     @OA\Property(property="occupancy_rate", type="number", example=16.67),
     *                     @OA\Property(property="occupancy_status", type="string", example="Available"),
     *                     @OA\Property(property="available_seat_numbers", type="array", @OA\Items(type="integer"), example={1,2,3,4,5}),
     *                     @OA\Property(property="booked_seat_numbers", type="array", @OA\Items(type="integer"), example={6,7,8,9,10})
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Trip not available")
     *         )
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

        $trip->load(['departureCity', 'arrivalCity', 'seats']);

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
                'available_seats' => $trip->available_seats,
                'total_seats' => $trip->total_seats,
                'additional_notes' => $trip->additional_notes,
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
                'seats_info' => [
                    'total_seats' => $trip->total_seats,
                    'available_seats' => $trip->available_seats,
                    'booked_seats' => $trip->booked_seats,
                    'occupancy_rate' => $trip->occupancy_rate,
                    'occupancy_status' => $trip->occupancy_status,
                    'seat_map' => $this->generateSeatMap($trip),
                    'available_seat_numbers' => $trip->seats()->available()->pluck('seat_number')->toArray(),
                    'booked_seat_numbers' => $trip->seats()->booked()->pluck('seat_number')->toArray(),
                    'detailed_seats' => $trip->seats()->orderBy('seat_number')->get()->map(function ($seat) {
                        return [
                            'seat_number' => $seat->seat_number,
                            'is_available' => $seat->is_available,
                            'status' => $seat->is_available ? 'available' : 'booked',
                            'status_label' => $seat->status_label,
                            'status_color' => $seat->status_color,
                            'booking_id' => $seat->booking_id,
                        ];
                    }),
                ],
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/trips/book",
     *     summary="Book a trip",
     *     description="Create a new trip booking with optional seat selection",
     *     tags={"Trips"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_id", "contact_name", "contact_email", "contact_phone", "booking_date", "number_of_passengers"},
     *             @OA\Property(property="trip_id", type="integer", example=1),
     *             @OA\Property(property="contact_name", type="string", example="John Doe"),
     *             @OA\Property(property="contact_email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="contact_phone", type="string", example="+201234567890"),
     *             @OA\Property(property="booking_date", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="number_of_passengers", type="integer", minimum=1, example=2),
     *             @OA\Property(property="selected_seats", type="array", @OA\Items(type="integer"), example={1, 2}),
     *             @OA\Property(property="special_requests", type="string"),
     *             @OA\Property(property="client_id", type="integer", description="Optional client ID for authenticated users")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Trip booked successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="booking_reference", type="string", example="TRIP-2024-0001"),
     *                 @OA\Property(property="selected_seats", type="array", @OA\Items(type="integer"), example={1, 2}),
     *                 @OA\Property(property="total_price", type="number", example=300.00),
     *                 @OA\Property(property="number_of_passengers", type="integer", example=2),
     *                 @OA\Property(property="booking_date", type="string", example="2024-01-15"),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Trip is not available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
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
        $booking = \App\Models\TripBooking::create([
            'trip_id' => $trip->id,
            'client_id' => $data['client_id'] ?? null,
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
        ]);

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

    private function generateSeatMap(Trip $trip)
    {
        $seats = $trip->seats()->orderBy('seat_number')->get();
        $seatMap = [];
        
        // Group seats by rows (assuming 4 seats per row for most vehicles)
        $seatsPerRow = 4;
        $totalSeats = $seats->count();
        $totalRows = ceil($totalSeats / $seatsPerRow);
        
        for ($row = 1; $row <= $totalRows; $row++) {
            $rowSeats = [];
            for ($col = 1; $col <= $seatsPerRow; $col++) {
                $seatNumber = ($row - 1) * $seatsPerRow + $col;
                
                if ($seatNumber <= $totalSeats) {
                    $seat = $seats->where('seat_number', $seatNumber)->first();
                    $rowSeats[] = [
                        'seat_number' => $seatNumber,
                        'is_available' => $seat ? $seat->is_available : false,
                        'status' => $seat ? ($seat->is_available ? 'available' : 'booked') : 'unavailable',
                        'status_label' => $seat ? $seat->status_label : 'غير متاح',
                        'status_color' => $seat ? $seat->status_color : 'secondary',
                        'booking_id' => $seat ? $seat->booking_id : null,
                    ];
                } else {
                    // Empty seat position
                    $rowSeats[] = [
                        'seat_number' => null,
                        'is_available' => false,
                        'status' => 'empty',
                        'status_label' => 'غير موجود',
                        'status_color' => 'light',
                        'booking_id' => null,
                    ];
                }
            }
            $seatMap[] = [
                'row' => $row,
                'seats' => $rowSeats
            ];
        }
        
        return [
            'total_seats' => $totalSeats,
            'total_rows' => $totalRows,
            'seats_per_row' => $seatsPerRow,
            'layout' => $seatMap,
            'summary' => [
                'available' => $seats->where('is_available', true)->count(),
                'booked' => $seats->where('is_available', false)->count(),
                'occupancy_rate' => $trip->occupancy_rate,
                'occupancy_status' => $trip->occupancy_status,
            ]
        ];
    }
}

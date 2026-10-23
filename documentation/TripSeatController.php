<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  schema="TripSeat",
 *  title="Trip Seat Schema",
 *  @OA\Property(property="seat_number", type="integer", example=1),
 *  @OA\Property(property="is_available", type="boolean", example=true),
 *  @OA\Property(property="status", type="string", example="متاح"),
 *  @OA\Property(property="status_color", type="string", example="success")
 * )
 */

/**
 * @OA\Schema(
 *  schema="SeatMapRow",
 *  title="Seat Map Row Schema",
 *  @OA\Property(property="row", type="integer", example=1),
 *  @OA\Property(property="seats", type="array", @OA\Items(ref="#/components/schemas/TripSeat"))
 * )
 */

/**
 * @OA\Schema(
 *  schema="TripSeatsResponse",
 *  title="Trip Seats Response Schema",
 *  @OA\Property(property="success", type="boolean", example=true),
 *  @OA\Property(property="data", type="object",
 *      @OA\Property(property="trip_id", type="integer", example=1),
 *      @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
 *      @OA\Property(property="total_seats", type="integer", example=100),
 *      @OA\Property(property="available_seats", type="integer", example=94),
 *      @OA\Property(property="seats", type="array", @OA\Items(ref="#/components/schemas/TripSeat"))
 *  )
 * )
 */

/**
 * @OA\Schema(
 *  schema="AvailableSeatsResponse",
 *  title="Available Seats Response Schema",
 *  @OA\Property(property="success", type="boolean", example=true),
 *  @OA\Property(property="data", type="object",
 *      @OA\Property(property="trip_id", type="integer", example=1),
 *      @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
 *      @OA\Property(property="available_seats", type="array", @OA\Items(type="integer"), example={1, 3, 4, 5, 6, 7, 8, 9, 10}),
 *      @OA\Property(property="available_count", type="integer", example=9)
 *  )
 * )
 */

/**
 * @OA\Schema(
 *  schema="BestSeatsResponse",
 *  title="Best Available Seats Response Schema",
 *  @OA\Property(property="success", type="boolean", example=true),
 *  @OA\Property(property="data", type="object",
 *      @OA\Property(property="trip_id", type="integer", example=1),
 *      @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
 *      @OA\Property(property="requested_count", type="string", example="3"),
 *      @OA\Property(property="best_available_seats", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
 *      @OA\Property(property="total_price", type="number", example=150)
 *  )
 * )
 */

/**
 * @OA\Schema(
 *  schema="SeatMapResponse",
 *  title="Seat Map Response Schema",
 *  @OA\Property(property="success", type="boolean", example=true),
 *  @OA\Property(property="data", type="object",
 *      @OA\Property(property="trip_id", type="integer", example=1),
 *      @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
 *      @OA\Property(property="total_seats", type="integer", example=100),
 *      @OA\Property(property="available_seats", type="integer", example=94),
 *      @OA\Property(property="seats_per_row", type="integer", example=4),
 *      @OA\Property(property="total_rows", type="integer", example=25),
 *      @OA\Property(property="seat_map", type="array", @OA\Items(
 *          type="object",
 *          @OA\Property(property="row", type="integer", example=1),
 *          @OA\Property(property="seats", type="array", @OA\Items(
 *              type="object",
 *              @OA\Property(property="seat_number", type="integer", example=1),
 *              @OA\Property(property="is_available", type="boolean", example=true),
 *              @OA\Property(property="status", type="string", example="متاح"),
 *              @OA\Property(property="status_color", type="string", example="success")
 *          ))
 *      ))
 *  )
 * )
 */

class TripSeatController extends Controller
{
    /**
     * Get all seats for a trip
     * @OA\Get(
     *     path="/api/trips/{trip}/seats",
     *     tags={"Seats"},
     *     summary="Get all seats for a trip",
     *     description="Retrieve all seats for a specific trip with their availability status",
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
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="trip_id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
     *                 @OA\Property(property="total_seats", type="integer", example=100),
     *                 @OA\Property(property="available_seats", type="integer", example=94),
     *                 @OA\Property(property="seats", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="seat_number", type="integer", example=1),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="status", type="string", example="متاح"),
     *                     @OA\Property(property="status_color", type="string", example="success")
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found or not available",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Trip is not available")
     *         )
     *     )
     * )
     */
    public function getSeats(Trip $trip)
    {
    }

    /**
     * Get available seats for a trip
     * @OA\Get(
     *     path="/api/trips/{trip}/seats/available",
     *     tags={"Seats"},
     *     summary="Get available seats for a trip",
     *     description="Retrieve only the available seats for a specific trip",
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
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="trip_id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
     *                 @OA\Property(property="available_seats", type="array", @OA\Items(type="integer"), example={1, 3, 4, 5, 6, 7, 8, 9, 10}),
     *                 @OA\Property(property="available_count", type="integer", example=9)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found or not available",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Trip is not available")
     *         )
     *     )
     * )
     */
    public function getAvailableSeats(Trip $trip)
    {
    }

    /**
     * Get best available seats for a trip
     * @OA\Get(
     *     path="/api/trips/{trip}/seats/best",
     *     tags={"Seats"},
     *     summary="Get best available seats for a trip",
     *     description="Get the best available seats for a specific number of passengers (consecutive seats if possible)",
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="Trip ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="count",
     *         in="query",
     *         description="Number of seats needed",
     *         required=true,
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="trip_id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
     *                 @OA\Property(property="requested_count", type="string", example="3"),
     *                 @OA\Property(property="best_available_seats", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *                 @OA\Property(property="total_price", type="number", example=150)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Not enough available seats",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not enough available seats")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found or not available",
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
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="count", type="array", @OA\Items(type="string"), example={"The count field is required."})
     *             )
     *         )
     *     )
     * )
     */
    public function getBestAvailableSeats(Request $request, Trip $trip)
    {
    }

    /**
     * Get seat map for a trip
     * @OA\Get(
     *     path="/api/trips/{trip}/seats/map",
     *     tags={"Seats"},
     *     summary="Get seat map for a trip",
     *     description="Get a visual representation of the seat map for a trip (organized in rows)",
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
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="trip_id", type="integer", example=1),
     *                 @OA\Property(property="trip_name", type="string", example="cairo to alexandria"),
     *                 @OA\Property(property="total_seats", type="integer", example=100),
     *                 @OA\Property(property="available_seats", type="integer", example=94),
     *                 @OA\Property(property="seats_per_row", type="integer", example=4),
     *                 @OA\Property(property="total_rows", type="integer", example=25),
     *                 @OA\Property(property="seat_map", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="row", type="integer", example=1),
     *                     @OA\Property(property="seats", type="array", @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="seat_number", type="integer", example=1),
     *                         @OA\Property(property="is_available", type="boolean", example=true),
     *                         @OA\Property(property="status", type="string", example="متاح"),
     *                         @OA\Property(property="status_color", type="string", example="success")
     *                     ))
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trip not found or not available",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Trip is not available")
     *         )
     *     )
     * )
     */
    public function getSeatMap(Trip $trip)
    {
    }
} 
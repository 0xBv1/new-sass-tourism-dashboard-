<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\CarRouteResource;
use App\Models\CarRoute;
use App\Traits\Response\HasApiResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;

class CarRentalController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Post(
     *     path="/api/car/rental/available/destinations",
     *     summary="Search For Avaiable Destinations For a Pickup Location",
     *     tags={"CarRentals"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="pickup_location_id",
     *                     type="number",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function availableDestinations()
    {
    }


    /**
     * @OA\Post(
     *     path="/api/car/rental/search/for/route",
     *     summary="Search For Avaiable Route",
     *     tags={"CarRentals"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="pickup_location_id",
     *                     type="number",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="destination_id",
     *                     type="number",
     *                     example=2
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function searchForRoute()
    {
    }


    /**
     * @OA\Post(
     *     path="/api/car/rental/checkout",
     *     summary="Car Rental Checkout",
     *     tags={"CarRentals"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="pickup_location_id",
     *                     type="number",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="destination_id",
     *                     type="number",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="adults",
     *                     type="number",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="children",
     *                     type="number",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="oneway",
     *                     type="boolean",
     *                     example=true
     *                 ),
     *                 @OA\Property(
     *                     property="pickup_date",
     *                     type="string",
     *                     example="2024-08-01"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_id",
     *                     type="string",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="pickup_time",
     *                     type="string",
     *                     example="14:00"
     *                 ),
     *                 @OA\Property(
     *                      property="return_date",
     *                      type="string",
     *                      example="2025-05-05"
     *                  ),
     *                 @OA\Property(
     *                      property="return_time",
     *                      type="string",
     *                      example="14:00"
     *                  ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Frank Djong"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="frank.djong@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     example="+9654411115852"
     *                 ),
     *                 @OA\Property(
     *                     property="nationality",
     *                     type="string",
     *                     example="Netherlands"
     *                 ),
     *                 @OA\Property(
     *                     property="stops",
     *                     type="array",
     *                     @OA\Items(type="number", example=4)
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function checkout()
    {
    }
}

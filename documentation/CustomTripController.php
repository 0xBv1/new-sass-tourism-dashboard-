<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BookingRequest;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

class CustomTripController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Post(
     *     path="/api/custom/trips",
     *     summary="Create Cutom Trip Request",
     *     tags={"CustomTrips"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="destination",
     *                     type="string",
     *                     example="egypt|other_countries",
     *                 ),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                      @OA\Items(type="number", example=1)
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     example="exact_time|approx_time|not_sure",
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="string",
     *                     example="2023-06-01",
     *                 ),
     *                 @OA\Property(
     *                     property="end_date",
     *                     type="string",
     *                     example="2023-07-01",
     *                 ),
     *                 @OA\Property(
     *                     property="month",
     *                     type="number",
     *                     example=11,
     *                 ),
     *                 @OA\Property(
     *                     property="days",
     *                     type="number",
     *                     example=25,
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     example="Ahmed",
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     example="Nasr",
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string",
     *                     example="01150225286",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="ahmednasr@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="nationality",
     *                     type="string",
     *                     example="Egypt"
     *                 ),
     *                 @OA\Property(
     *                     property="adults",
     *                     type="number",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="children",
     *                     type="number",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="infants",
     *                     type="number",
     *                     example=1,
     *                 ),
     *                 @OA\Property(
     *                     property="min_person_budget",
     *                     type="number",
     *                     example=1000.00,
     *                 ),
     *                 @OA\Property(
     *                     property="max_person_budget",
     *                     type="number",
     *                     example=2000.00,
     *                 ),
     *                 @OA\Property(
     *                     property="flight_offer",
     *                     type="boolean",
     *                     example=false,
     *                 ),
     *                 @OA\Property(
     *                     property="additional_notes",
     *                     type="string",
     *                     example="Any Special Notes",
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
    public function store()
    {
    }
}

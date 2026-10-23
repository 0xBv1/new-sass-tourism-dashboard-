<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BookingRequest;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Booking
     * @OA\Get (
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *    @OA\Parameter(
     *         description="this key is used to select the columns the need to return instead of return all columns example: id,created_at,updated_at",
     *         in="query",
     *         name="columns",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: tours,coupon,client,currency",
     *         in="query",
     *         name="includes",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to select the page number that need to return example: page=2",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(type="number"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to the max result that should return per page example: limit=25",
     *         in="query",
     *         name="page_limit",
     *         required=false,
     *         @OA\Schema(type="number"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to sort the result that return per page example: order_by=id,asc|id,desc",
     *         in="query",
     *         name="order_by",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *         @OA\Property(
     *             property="current_page",
     *             type="number",
     *             example=1
     *             ),
     *         @OA\Property(
     *             property="from",
     *             type="number",
     *             example=1
     *             ),
     *         @OA\Property(
     *             property="last_page",
     *             type="number",
     *             example=10
     *             ),
     *         @OA\Property(
     *             property="next_page_url",
     *             type="string",
     *             example="http://baseURL/bookings?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/bookings"
     *             ),
     *         @OA\Property(
     *             property="per_page",
     *             type="number",
     *             example=15
     *             ),
     *         @OA\Property(
     *             property="prev_page_url",
     *             type="string",
     *             example="null"
     *             ),
     *         @OA\Property(
     *             property="to",
     *             type="number",
     *             example=15
     *             ),
     *         @OA\Property(
     *             property="total",
     *             type="number",
     *             example=350
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
    }

    /**
     * Get Detail Booking
     * @OA\Get (
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-12-11T09:25:53.000000Z"
     *                     )
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create New Order",
     *     tags={"Bookings"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
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
     *                     property="phone",
     *                     type="string",
     *                     example="+201150225286",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="ahmednasr@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="country",
     *                     type="string",
     *                     example="Egypt"
     *                 ),
     *                 @OA\Property(
     *                      property="start_date",
     *                      type="string",
     *                      example="2023-12-01"
     *                  ),
     *                 @OA\Property(
     *                     property="state",
     *                     type="string",
     *                     example="Cairo"
     *                 ),
     *                 @OA\Property(
     *                     property="street_address",
     *                     type="string",
     *                     example="28 Salah Salem st,"
     *                 ),
     *                 @OA\Property(
     *                     property="payment_method",
     *                     type="string",
     *                     example="paypal"
     *                 ),
     *                 @OA\Property(
     *                     property="currency_id",
     *                     type="number",
     *                     example=1,
     *                 ),
     *                 @OA\Property(
     *                     property="coupon_id",
     *                     type="number",
     *                     example=1,
     *                 ),
     *                 @OA\Property(
     *                     property="notes",
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
    public function create(BookingRequest $request)
    {
    }
}

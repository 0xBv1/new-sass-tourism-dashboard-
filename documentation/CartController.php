<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Traits\Response\HasApiResponse;

class CartController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/cart/list",
     *     summary="List Cart Items",
     *     tags={"Cart"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                     @OA\Property(
     *                         property="status",
     *                         type="boolean",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example="Cart loadded successfully"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(type="object",oneOf={@OA\Schema(ref="#/components/schemas/CartItem")},)
     *                     ),
     *          )
     *     )
     * )
     */
    public function list()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/cart/tours/append",
     *     summary="Append Cart Item",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="tour_id",
     *                     type="number",
     *                     description="Tour ID in database",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="adults",
     *                     type="number",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="children",
     *                     type="number",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="infants",
     *                     type="number",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="start_date",
     *                     type="string",
     *                     example="2025-01-01"
     *                 ),
     *                 @OA\Property(
     *                     property="options",
     *                     type="array",
     *                     description="List of Tour option IDs in database",
     *                     @OA\Items(type="number",example=1)
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
    public function appendTour(LoginRequest $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/cart/rentals/append",
     *     summary="Append Cart Item",
     *     tags={"Cart"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
    *                   @OA\Property(
     *                      property="pickup_location_id",
     *                      type="number",
     *                      example=1
     *                  ),
     *                  @OA\Property(
     *                      property="destination_id",
     *                      type="number",
     *                      example=2
     *                  ),
     *                  @OA\Property(
     *                      property="adults",
     *                      type="number",
     *                      example=2
     *                  ),
     *                  @OA\Property(
     *                      property="children",
     *                      type="number",
     *                      example=0
     *                  ),
     *                  @OA\Property(
     *                      property="oneway",
     *                      type="boolean",
     *                      example=true
     *                  ),
     *                  @OA\Property(
     *                      property="pickup_date",
     *                      type="string",
     *                      example="2025-08-01"
     *                  ),
     *                  @OA\Property(
     *                      property="pickup_time",
     *                      type="string",
     *                      example="14:00"
     *                  ),
     *                  @OA\Property(
     *                      property="stops",
     *                      type="array",
     *                      @OA\Items(type="number", example=4)
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function appendRental(LoginRequest $request)
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/remove/{tour}",
     *     summary="Remove Item From Cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         in="path",
     *         name="tour",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function remove()
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/clear",
     *     summary="Clear Cart",
     *     tags={"Cart"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function clear()
    {
    }

}

<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="TourOption",
 *  title="Tour Option Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         example="Private Guide"
 *                     ),
 *                     @OA\Property(
 *                         property="description",
 *                         type="string",
 *                         example="Add a private guide to your tour for a more personalized experience"
 *                     ),
 *                     @OA\Property(
 *                         property="adult_price",
 *                         type="number",
 *                         format="float",
 *                         example="50.00"
 *                     ),
 *                     @OA\Property(
 *                         property="child_price",
 *                         type="number",
 *                         format="float",
 *                         example="25.00"
 *                     ),
 *                     @OA\Property(
 *                         property="pricing_groups",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(
 *                                 property="from",
 *                                 type="number",
 *                                 example="1"
 *                             ),
 *                             @OA\Property(
 *                                 property="to",
 *                                 type="number",
 *                                 example="5"
 *                             ),
 *                             @OA\Property(
 *                                 property="price",
 *                                 type="number",
 *                                 format="float",
 *                                 example="50.00"
 *                             ),
 *                             @OA\Property(
 *                                 property="child_price",
 *                                 type="number",
 *                                 format="float",
 *                                 example="25.00"
 *                             )
 *                         )
 *                     ),
 * )
 */
class TourOptionController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Tour Options
     * @OA\Get (
     *     path="/api/tour-options",
     *     tags={"Tour Options"},
     *    @OA\Parameter(
     *         description="Filter Tour Options By Name",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to select the columns the need to return instead of return all columns example: id,created_at,updated_at",
     *         in="query",
     *         name="columns",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: tours",
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
     *             example="http://baseURL/tour-options?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/tour-options"
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
     *             example=150
     *             ),
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                  oneOf={@OA\Schema(ref="#/components/schemas/TourOption")},
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
     * Get Detail Tour Option
     * @OA\Get (
     *     path="/api/tour-options/{id}",
     *     tags={"Tour Options"},
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
     *          @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Data has been loaded successfully."
     *              ),
     *              @OA\Property(
     *                  property="status",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  oneOf={@OA\Schema(ref="#/components/schemas/TourOption")},
     *              ),
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }

    /**
     * Get Tour Options by Tour ID
     * @OA\Get (
     *     path="/api/tour-options/tour/{tourId}",
     *     tags={"Tour Options"},
     *     @OA\Parameter(
     *         in="path",
     *         name="tourId",
     *         required=true,
     *         description="Tour ID to get options for",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *          @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Data has been loaded successfully."
     *              ),
     *              @OA\Property(
     *                  property="status",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      oneOf={@OA\Schema(ref="#/components/schemas/TourOption")},
     *                  )
     *              ),
     *         )
     *     )
     * )
     */
    public function getByTour(int $tourId)
    {
    }
}

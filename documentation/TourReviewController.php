<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\TourReviewResource;
use App\Models\TourReview;
use App\Traits\Response\HasApiResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;

/**
 * @OA\Schema(
 *  schema="TourReview",
 *  title="Tour Review Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="rate",
 *                         type="number",
 *                         example="5"
 *                     ),
 *                     @OA\Property(
 *                         property="content",
 *                         type="string",
 *                         example="This tour is amazing!"
 *                     ),
 *                     @OA\Property(
 *                         property="created_at",
 *                         type="string",
 *                         example="2023-12-11T09:25:53.000000Z"
 *                     ),
 *                     @OA\Property(
 *                         property="updated_at",
 *                         type="string",
 *                         example="2023-12-11T09:25:53.000000Z"
 *                     )
 * )
 */
class TourReviewController extends Controller
{
    use HasApiResponse;

    /**
     * Get List TourReview
     * @OA\Get (
     *     path="/api/tour-reviews",
     *     tags={"TourReviews"},
     *    @OA\Parameter(
     *         description="Filter TourReviews By Name",
     *         in="query",
     *         name="tour_id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: type,user",
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
     *             example="http://baseURL/tourreviews?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/tourreviews"
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
     *                      oneOf={@OA\Schema(ref="#/components/schemas/TourReview")}
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
     * @OA\Post(
     *     path="/api/tour-reviews",
     *     summary="Add Tour Review",
     *     tags={"TourReviews"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="tour_id",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="rate",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="reviewer_name",
     *                     type="string",
     *                 ),
     *                 example={"tour_id": 1, "rate": 3, "content": "This Tour Is Amazing", "reviewer_name": "Jack Adams"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *     )
     * )
     */
    public function store()
    {
    }
}

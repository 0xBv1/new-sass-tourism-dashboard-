<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Destination",
 *  title="Destination Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="title",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="description",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="slug",
 *                         type="string",
 *                         example="cairo"
 *                     ),
 *                     @OA\Property(
 *                         property="enabled",
 *                         type="boolean",
 *                         example="true"
 *                     ),
 *                     @OA\Property(
 *                         property="featured",
 *                         type="boolean",
 *                         example="true"
 *                     ),
 *                     @OA\Property(
 *                         property="banner",
 *                         type="string",
 *                         example="htttp://baseUrl/storage/image.png"
 *                     ),
 *                     @OA\Property(
 *                         property="featured_image",
 *                         type="string",
 *                         example="htttp://baseUrl/storage/image.png"
 *                     ),
 *                     @OA\Property(
 *                         property="gallery",
 *                         type="array",
 *                          @OA\Items(
 *                              type="string",
 *                              example="https://baseUrl/storage/products/image1.jpg"
 *                          )
 *                     ),
 *                  @OA\Property(
 *                          property="seo",
 *                          type="object",
 *                          oneOf={@OA\Schema(ref="#/components/schemas/Seo")},
 *                  ),
 * )
 */
class DestinationController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Destination
     * @OA\Get (
     *     path="/api/destinations",
     *     tags={"Destinations"},
     *    @OA\Parameter(
     *         description="Filter Destinations By Name",
     *         in="query",
     *         name="title",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="Filter Destinations By Name",
     *         in="query",
     *         name="slug",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="Filter Destinations By Parent",
     *         in="query",
     *         name="parent_id",
     *         required=false,
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to select the columns the need to return instead of return all columns example: id,created_at,updated_at",
     *         in="query",
     *         name="columns",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: tours,parent,children,seo",
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
     *             example="http://baseURL/destinations?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/destinations"
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Destination")},
     *                 )
     *             )
     *
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
    }

    /**
     * Get Detail Destination
     * @OA\Get (
     *     path="/api/destinations/{slug}",
     *     tags={"Destinations"},
     *     @OA\Parameter(
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: tours,parent,children,seo",
     *         in="query",
     *         name="includes",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Destination")},
     *              ),
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }
}

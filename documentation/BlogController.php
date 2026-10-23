<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\BlogResource;
use App\Models\Blog;
use App\Traits\Response\HasApiResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;

/**
 * @OA\Schema(
 *  schema="Blog",
 *  title="Blog Schema",
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
 *                         property="slug",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="description",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="active",
 *                         type="boolean",
 *                        example="true"
 *                     ),
 *                     @OA\Property(
 *                         property="published_at",
 *                         type="string",
 *                        example="2023-12-11T09:25:53.000000Z"
 *                     ),
 *                     @OA\Property(
 *                         property="tags",
 *                         type="string",
 *                        example="HTML,JS,CSS"
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
class BlogController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Blog
     * @OA\Get (
     *     path="/api/blogs",
     *     tags={"Blogs"},
     *    @OA\Parameter(
     *         description="Filter Blogs By title",
     *         in="query",
     *         name="title",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="Filter Blog Category",
     *         in="query",
     *         name="category_id",
     *         required=false,
     *         @OA\Schema(type="number"),
     *     ),

     *    @OA\Parameter(
     *         description="this key is used to select the columns the need to return instead of return all columns example: id,created_at,updated_at",
     *         in="query",
     *         name="columns",
     *         required=false,
     *         @OA\Schema(type="string"),
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
     *             example="http://baseURL/blogs?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/blogs"
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
     *                     oneOf={@OA\Schema(ref="#/components/schemas/Blog")},
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
     * Get Detail Blog
     * @OA\Get (
     *     path="/api/blogs/{slug}",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(type="string")
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Blog")},
     *              ),
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }
}

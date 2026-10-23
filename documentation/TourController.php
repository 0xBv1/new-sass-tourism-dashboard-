<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Tour",
 *  title="Tour Schema",
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
 *                         property="overview",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="highlights",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="rate",
 *                         type="number",
 *                         example=5
 *                     ),
 *                      @OA\Property(
 *                         property="duration_in_days",
 *                         type="number",
 *                         example=5
 *                     ),
 *                     @OA\Property(
 *                         property="reviews_number",
 *                         type="number",
 *                         example=1
 *                     ),
 *                     @OA\Property(
 *                         property="itinary",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="included",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="excluded",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="duration",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="type",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="run",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="pickup_time",
 *                         type="string",
 *                         example="Lorem Ipsum"
 *                     ),
 *                     @OA\Property(
 *                         property="enabled",
 *                         type="boolean",
 *                        example="true"
 *                     ),
 *                     @OA\Property(
 *                         property="featured",
 *                         type="boolean",
 *                        example="true"
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
 *                     @OA\Property(
 *                         property="start_from",
 *                         type="number",
 *                         description="Get tour lowest price",
 *                        example="20"
 *                     ),
 *                     @OA\Property(
 *                         property="adult_price",
 *                         type="number",
 *                        example="25.5"
 *                     ),
 *                     @OA\Property(
 *                         property="child_price",
 *                         type="number",
 *                        example="15"
 *                     ),
 *                     @OA\Property(
 *                         property="infant_price",
 *                         type="number",
 *                        example="10"
 *                     ),
 *                     @OA\Property(
 *                         property="pricing_groups",
 *                         type="array",
 *                      @OA\Items(
 *                              type="object",
 *                              @OA\Property(
 *                                  property="from",
 *                                  type="number",
 *                                  example="1"
 *                              ),
 *                              @OA\Property(
 *                                  property="to",
 *                                  type="number",
 *                                  example="5"
 *                              ),
 *                              @OA\Property(
 *                                  property="price",
 *                                  type="number",
 *                                  example="100"
 *                              ),
 *                             @OA\Property(
 *                                  property="child_price",
 *                                  type="number",
 *                                  example="50"
 *                              ),
 *                          )
 *                     ),
 *                  @OA\Property(
 *                          property="seo",
 *                          type="object",
 *                          oneOf={@OA\Schema(ref="#/components/schemas/Seo")},
 *                  ),
 *                  @OA\Property(
 *                       property="categories",
 *                       type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          oneOf={@OA\Schema(ref="#/components/schemas/Category")},
 *                      )
 *                  ),
 *                  @OA\Property(
 *                       property="options",
 *                       type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          oneOf={@OA\Schema(ref="#/components/schemas/TourOption")},
 *                      )
 *                  ),
 *                  @OA\Property(
 *                       property="destinations",
 *                       type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          oneOf={@OA\Schema(ref="#/components/schemas/Destination")},
 *                      )
 *                  ),
 * )
 */
class TourController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Tour
     * @OA\Get (
     *     path="/api/tours",
     *     tags={"Tours"},
     *    @OA\Parameter(
     *         description="Filter Tours By Title",
     *         in="query",
     *         name="title",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="Filter Tours By Slug",
     *         in="query",
     *         name="slug",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="Filter Tours Duration In Days",
     *         in="query",
     *         name="duration_in_days",
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
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: seo,options,categories,destinations,wishlisted",
     *         in="query",
     *         name="includes",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: seo,options,categories,destinations,wishlisted",
     *         in="query",
     *         name="exists",
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
     *             example="http://baseURL/tours?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/tours"
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Tour")},
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
     * Get Detail Tour
     * @OA\Get (
     *     path="/api/tours/{slug}",
     *     tags={"Tours"},
     *     @OA\Parameter(
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: options,categories,destinations,seo",
     *         in="query",
     *         name="includes",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Parameter(
     *          description="this key is used to serialize related objects by includes the objects name using comma separated example: seo,options,categories,destinations,wishlisted",
     *          in="query",
     *          name="exists",
     *          required=false,
     *          @OA\Schema(type="string"),
     *      ),
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Tour")},
     *              ),
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }


    /**
     * Get stats for all Tours
     * @OA\Get (
     *     path="/api/tours/stats",
     *     tags={"Tours"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *          @OA\Property(
     *                  property="pricing",
     *                  type="object",
     *                            @OA\Property(property="min_price", type="integer",example=0),
     *                            @OA\Property(property="max_price", type="integer",example=100),
     *              ),
     *         )
     *     )
     * )
     */
    public function stats()
    {
    }
}

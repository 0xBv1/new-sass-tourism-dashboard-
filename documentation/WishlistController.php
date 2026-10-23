<?php

namespace Documentation;

use App\Http\Controllers\Controller;

class WishlistController extends Controller
{

    /**
     * Get Client's Wishlist
     * @OA\Get (
     *     path="/api/wishlist",
     *     tags={"Wishlist"},
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
     *             example="http://baseURL/wishlist?page=2"
     *             ),
     *         @OA\Property(
     *             property="path",
     *             type="string",
     *             example="http://baseURL/wishlist"
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
    public function index()
    {
    }

    /**
     * @OA\Put(
     *     path="/api/wishlist/{tour}/toggle",
     *     summary="Toggle Tour In Wishlist",
     *     tags={"Wishlist"},
     *     @OA\Parameter(
     *          in="path",
     *          name="tour",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content",
     *     )
     * )
     */
    public function toggle()
    {
    }
}

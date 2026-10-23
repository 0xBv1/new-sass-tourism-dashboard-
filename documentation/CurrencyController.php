<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Currency",
 *  title="Currency Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         example="USD"
 *                     ),
 *                     @OA\Property(
 *                         property="symbol",
 *                         type="string",
 *                         example="$"
 *                     ),
 *                     @OA\Property(
 *                         property="exchange_rate",
 *                         type="number",
 *                         example=1
 *                     ),
 *                     @OA\Property(
 *                         property="icon",
 *                         type="string",
 *                         example="https://baseUrl/storage/currencies/usd.png"
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
class CurrencyController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Currency
     * @OA\Get (
     *     path="/api/currencies",
     *     tags={"Currencies"},
     *    @OA\Parameter(
     *         description="Filter Currencies By Name",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     oneOf={
     *                          @OA\Schema(ref="#/components/schemas/Currency"),
     *                     },
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
     * Get Detail Currency
     * @OA\Get (
     *     path="/api/currencies/{id}",
     *     tags={"Currencies"},
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
     *                         property="data",
     *                         type="object",
     *                          oneOf={
     *                              @OA\Schema(ref="#/components/schemas/Currency"),
     *                          },
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="boolean",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         example=""
     *                     )
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }
}

<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\HasApiResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  schema="City",
 *  title="City Schema",
 *  @OA\Property(
 *      property="id",
 *      type="integer",
 *      example=1
 *  ),
 *  @OA\Property(
 *      property="name",
 *      type="string",
 *      example="Cairo"
 *  ),
 *  @OA\Property(
 *      property="slug",
 *      type="string",
 *      example="cairo"
 *  )
 * )
 */

class CityController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Cities
     * @OA\Get (
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Get all cities",
     *     description="Retrieve a list of all available cities for trip booking",
     *     @OA\Parameter(
     *         description="Search city by ID",
     *         in="query",
     *         name="id",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Search cities by name",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Cairo"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="cairo"
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
} 
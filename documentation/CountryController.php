<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;

/**
 * @OA\Schema(
 *  schema="Country",
 *  title="Country Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="code",
 *                         type="string",
 *                         example="EG"
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         example="Egypt"
 *                     ),
 *                     @OA\Property(
 *                         property="flag",
 *                         type="string",
 *                         example="🇪🇬"
 *                     ),
 *                     @OA\Property(
 *                         property="phone_code",
 *                         type="string",
 *                         example="+2"
 *                     ),
 *                     @OA\Property(
 *                         property="active",
 *                         type="boolean",
 *                         example="true"
 *                     ),
 *                     @OA\Property(
 *                         property="states",
 *                         type="array",
 *                          @OA\Items(
 *                              type="object",
 *                              oneOf={@OA\Schema(ref="#/components/schemas/State")},
 *                          )
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
class CountryController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get (
     *     path="/api/countries",
     *     summary="List All Countries",
     *     tags={"Countries"},
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
     *                         example=""
     *                     ),
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                     type="object",
     *                     oneOf={
     *                          @OA\Schema(ref="#/components/schemas/Country"),
     *                     },
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
     * Get Detail Country
     * @OA\Get (
     *     path="/api/countries/{id}",
     *     tags={"Countries"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *    @OA\Parameter(
     *         description="this key is used to serialize related objects by includes the objects name using comma separated example: states",
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
     *                  oneOf={@OA\Schema(ref="#/components/schemas/Country")},
     *              ),
     *         )
     *     )
     * )
     */
    public function show(mixed $id)
    {
    }
}

<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *  schema="Setting",
 *  title="Setting Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="option_key",
 *                         type="string",
 *                         example="social_links"
 *                     ),
 *                     @OA\Property(
 *                         property="option_value",
 *                         type="array",
 *                         @OA\Items()
 *                     ),
 * )
 */
class SettingController extends Controller
{
    use HasApiResponse;

    /**
     * Get List Page
     * @OA\Get (
     *     path="/api/settings",
     *     tags={"Settings"},
     *    @OA\Parameter(
     *         description="Filter Pages By Option key",
     *         in="query",
     *         name="option_key",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *         @OA\Property(
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(
     *                     type="object",
     *                      oneOf={@OA\Schema(ref="#/components/schemas/Setting")},
     *                  )
     *             )
     *         )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
    }


}

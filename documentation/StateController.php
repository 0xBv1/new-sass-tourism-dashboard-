<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;

/**
 * @OA\Schema(
 *  schema="State",
 *  title="State Schema",
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
 *                         property="country_id",
 *                         type="number",
 *                         example="50"
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
class StateController extends Controller
{
}

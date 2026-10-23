<?php

namespace Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewRequest;
use App\Models\Coupon;
use App\Traits\Response\HasApiResponse;

/**
 * @OA\Schema(
 *  schema="Coupon",
 *  title="Coupon Schema",
 *                     @OA\Property(
 *                         property="id",
 *                         type="number",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="code",
 *                         type="string",
 *                         example="TZ-2023"
 *                     ),
 *                     @OA\Property(
 *                         property="title",
 *                         type="string",
 *                         example="Tizkar Launch"
 *                     ),
 *                     @OA\Property(
 *                         property="discount_type",
 *                         type="string",
 *                         example="percentage"
 *                     ),
 *                     @OA\Property(
 *                         property="value",
 *                         type="number",
 *                         example="50.00"
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
class CouponController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get (
     *     path="/api/coupons/{code}/validate",
     *     summary="Validate Coupone",
     *     tags={"Coupons"},
     *    @OA\Parameter(
     *         description="Code",
     *         in="path",
     *         name="code",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
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
     *                     @OA\Property(
     *                         property="data",
     *                         type="object",
     *                         oneOf={@OA\Schema(ref="#/components/schemas/Coupon")},
     *                     )
     *         )
     *     )
     * )
     */
    public function ValidateCoupon(Coupon $coupon)
    {
    }
}

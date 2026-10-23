<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class CouponController extends Controller
{
    use HasApiResponse;


    /**
     * Validate Coupon
     * @param Coupon $coupon
     * @return JsonResponse
     */
    public function validateCoupon(Coupon $coupon)
    {
        try {
            $coupon->validate();
            return $this->send(data: $coupon, message: __('messages.coupons.applied'));
        } catch (Exception|Throwable $exception) {
            report($exception);
            return $this->send(message: $exception->getMessage(), statusCode: Response::HTTP_BAD_REQUEST);
        }
    }
}

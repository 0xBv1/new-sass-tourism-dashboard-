<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddCartHotelRoomBookingRequest;
use App\Services\Client\Cart;
use App\Traits\Response\HasApiResponse;
use Symfony\Component\HttpFoundation\Response;

class HotelRoomBookingController extends Controller
{
    use HasApiResponse;

    public function addToCart(AddCartHotelRoomBookingRequest $request)
    {
        try {
            $cart = new Cart();
            $cartData = $request->getSanitized();
            
            $cart->appendHotelRoomBooking($cartData);

            return $this->send(
                data: null, 
                message: __('messages.cart.hotel_booking_add_to_cart_successfully'),
                statusCode: Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->send(
                message: $e->getMessage(), 
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }
    }
}

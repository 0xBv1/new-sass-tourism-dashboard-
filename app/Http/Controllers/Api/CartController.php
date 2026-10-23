<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TourIsNotAvailableAtDateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddCartRentalRequest;
use App\Http\Requests\Api\Cart\AddCartTourRequest;
use App\Http\Resources\Api\CartResource;
use App\Services\Client\Cart;
use App\Traits\Response\HasApiResponse;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    use HasApiResponse;

    public function __construct(private readonly Cart $cart = new Cart)
    {
    }

    public function list()
    {
        $items = $this->cart->load();

        return $this->send(
            data: CartResource::collection($items),
            message: $items->isEmpty() ? __('messages.cart.empty') : __('messages.cart.loaded')
        );
    }

    public function appendTour(AddCartTourRequest $request)
    {
        try {
            $cartItem = $request->getSanitized();

            $this->cart->appendTour($cartItem);

            return $this->send(data: null, message: __('messages.cart.tour_add_to_cart_successfully'));
        } catch (TourIsNotAvailableAtDateException $exception) {
            return $this->send(message: $exception->getMessage(), statusCode: Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return $this->send(message: __('messages.bookings.error'), statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws \Throwable
     */
    public function appendRental(AddCartRentalRequest $request)
    {
        $cartItem = $request->getSanitized();

        $this->cart->appendRental($cartItem);

        return $this->send(data: null, message: __('messages.cart.rental_add_to_cart_successfully'));

    }

    public function remove($item)
    {
        $this->cart->remove($item);

        return $this->send(message: __('messages.cart.removed_tour'));
    }

    public function clear()
    {
        $this->cart->clear();
        return $this->send(message: __('messages.cart.cleared'));
    }
}

<?php

namespace App\Services\Client;

use App\Enums\CartItemType;
use App\Exceptions\EmptyCartException;
use App\Models\Booking as BookingModel;
use App\Models\BookingTour;
use App\Models\Coupon;
use App\Models\Tour;
use App\Models\TourOption;
use Exception;
use Throwable;

class Booking
{
    private BookingModel|null $booking = null;

    public function __construct(private readonly Cart $cart = new Cart)
    {
    }

    public function getBooking(): ?BookingModel
    {
        return $this->booking;
    }


    /**
     * @throws EmptyCartException
     * @throws Exception|Throwable
     */
    public function create(array $request = []): bool
    {
        $this->cart->load();

        if ($this->cart->items->isEmpty()) {
            throw new EmptyCartException;
        }

        $coupon = null;

        if (isset($request['coupon_id'])) {
            $coupon = Coupon::find($request['coupon_id']);
            $coupon?->validate();
        }

        $request['sub_total_price'] = 0;
        $request['total_price'] = 0;

        $this->booking = BookingModel::create($request);

        $this->associateTours();

        $this->associateRentals();

        $this->booking->refresh();

        if ($coupon) {
            $priceAfterDetection = $coupon->apply($this->booking->total_price);
            $this->booking->update(['total_price' => $priceAfterDetection]);
        }

        $this->cart->clear();

        return true;
    }

    private function associateTours(): void
    {
        $subTotal = $this->booking->sub_total_price ?? 0;
        $total = $this->booking->total_price ?? 0;
        $tours = Tour::whereIn('id', $this->cart->items->where('item_type', CartItemType::TOUR->value)->pluck('tour_id')->toArray())->get();
        foreach ($this->cart->items->where('item_type', CartItemType::TOUR->value) as $cartItem) {
            $tour = $tours->firstWhere('id', $cartItem['tour_id']);

            $options = empty($cartItem['options']) ? [] :
                $tour->options()
                    ->whereIn('tour_options.id', $cartItem['options'])->get()
                    ->map(fn(TourOption $tourOption) => [
                        'id' => $tourOption->id,
                        'adult_price' => $tourOption->calcAdultPrice($cartItem['adults']),
                        'child_price' => $tourOption->calcChildPrice($cartItem['adults']),
                    ])->toArray();

            $bookingTour = [
                'start_date' => $cartItem['start_date'],
                'tour_id' => $cartItem['tour_id'],
                'adults' => $cartItem['adults'],
                'children' => $cartItem['children'],
                'infants' => $cartItem['infants'],
                'options' => $options,
                'adult_price' => $tour->calcAdultPrice($cartItem['adults'], $cartItem['start_date']),
                'child_price' => $tour->calcChildPrice($cartItem['adults'], $cartItem['start_date']),
                'infant_price' => $tour->infant_price,
                'booking_id' => $this->booking->id,
            ];

            BookingTour::create($bookingTour);

            $adultsPrice = $bookingTour['adult_price'] * $bookingTour['adults'];
            $childrenPrice = $bookingTour['child_price'] * $bookingTour['children'];
            $infantsPrice = $bookingTour['infants'] * $bookingTour['infant_price'];
            $optionsPrice = 0;

            foreach ($options as $option) {
                $optionsPrice += ($cartItem['adults'] * $option['adult_price']) + ($cartItem['children'] * $option['child_price']);
            }

            $subTotal += $adultsPrice + $childrenPrice + $infantsPrice + $optionsPrice;
            $total += $adultsPrice + $childrenPrice + $infantsPrice + $optionsPrice;
        }

        $this->booking->update([
            'sub_total_price' => $subTotal,
            'total_price' => $total
        ]);
    }

    private function associateRentals(): void
    {
        $subTotal = $this->booking->sub_total_price ?? 0;
        $total = $this->booking->total_price ?? 0;
        foreach ($this->cart->items->where('item_type', CartItemType::RENTAL->value) as $cartItem) {
            $rental = $this->booking->rentals()->create([
                'pickup_location_id' => $cartItem['pickup_location_id'],
                'destination_id' => $cartItem['destination_id'],
                'adults' => $cartItem['adults'],
                'children' => $cartItem['children'],
                'car_route_price' => $cartItem['car_route_price'],
                'car_type' => $cartItem['car_type'],
                'oneway' => $cartItem['oneway'],
                'pickup_date' => $cartItem['pickup_date'],
                'pickup_time' => $cartItem['pickup_time'],
                'name' => $this->booking->name,
                'email' => $this->booking->email,
                'phone' => $this->booking->phone,
                'nationality' => $this->booking->country,
                'currency_id' => $this->booking->currency_id,
                'currency_exchange_rate' => $this->booking->currency_exchange_rate
            ]);
            foreach ($cartItem['stops'] as $stop) {
                $rental->stops()->create([
                    'stop_location_id' => $stop['id'],
                    'price' => $stop['price'],
                ]);
            }
            $subTotal += $rental->car_route_price + collect($cartItem['stops'])->sum('price');
            $total += $rental->car_route_price + collect($cartItem['stops'])->sum('price');
        }

        $this->booking->update([
            'sub_total_price' => $subTotal,
            'total_price' => $total
        ]);
    }
}

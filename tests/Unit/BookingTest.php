<?php

namespace Tests\Unit;

use App\Enums\CouponType;
use App\Exceptions\EmptyCartException;
use App\Exceptions\ExpiredCouponException;
use App\Models\Booking;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Tour;
use App\Models\TourOption;
use App\Models\TourSeason;
use App\Services\Client\Booking as BookingService;
use App\Services\Client\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_create_booking_with_empty_cart()
    {
        $bookingService = new BookingService;
        $bookingRequest = Booking::factory()->make()->toArray();
        try {
            $bookingService->create($bookingRequest);
            $this->fail('Booking is created without cart');
        } catch (EmptyCartException $exception) {
            $this->assertTrue(true);
        }
    }

    public function test_create_booking_successfully()
    {
        $cartService = new Cart();
        $tour = Tour::factory()->create();

        $user_cart = $cartService->getCart();
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id
        ])->make()->toArray();
        $cartService->appendTour($cartItem);

        $bookingRequest = Booking::factory([
            'coupon_id' => null
        ])->make()->toArray();
        foreach (['created_at', 'updated_at', 'deleted_at', 'total_price', 'sub_total_price'] as $k) {
            unset($bookingRequest[$k]);
        }
        $bookingService = new BookingService;

        $bookingService->create($bookingRequest);
        $this->assertDatabaseHas('bookings', $bookingRequest);
    }

    public function test_calculate_sub_total_price_of_booking_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 10;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,

        ])->create();
        $user_cart = $cartService->getCart();
        $adults = 1;
        $children = 1;
        $infants = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $bookingRequest = Booking::factory([
            'coupon_id' => null
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $sub_total_price = ($adult_price * $adults) + ($child_price * $children) + ($infant_price * $infants);
        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->sub_total_price, 3));
    }

    public function test_calculate_total_price_of_booking_without_coupon_discount_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 15;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,

        ])->create();
        $user_cart = $cartService->getCart();
        $adults = 1;
        $children = 1;
        $infants = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $bookingRequest = Booking::factory([
            'coupon_id' => null
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $total_price = ($adult_price * $adults) + ($child_price * $children) + ($infant_price * $infants);
        $this->assertEquals(number_format($total_price, 3), number_format($booking->total_price, 3));
    }

    public function test_calculate_total_price_of_booking_with_coupon_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 12;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
        ])->create();
        $user_cart = $cartService->getCart();
        $adults = 1;
        $children = 1;
        $infants = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $coupon = Coupon::factory([
            'start_date' => null,
            'end_date' => null,
            'active' => true,
            'limit_per_customer' => null,
            'limit_per_usage' => null,
        ])->create();
        $bookingRequest = Booking::factory([
            'coupon_id' => $coupon->id
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $total_price = ($adult_price * $adults) + ($child_price * $children) + ($infant_price * $infants);
        $this->assertEquals(number_format($coupon->apply($total_price), 3), number_format($booking->total_price, 3));
    }

    public function test_create_booking_with_expired_coupon_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,

        ])->create();
        $user_cart = $cartService->getCart();
        $adults = 1;
        $children = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $coupon = Coupon::factory([
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(3),
            'active' => true,
            'limit_per_customer' => null,
            'limit_per_usage' => null,
        ])->create();
        $bookingRequest = Booking::factory([
            'coupon_id' => $coupon->id
        ])->make()->toArray();

        $bookingService = new BookingService;

        try {
            $bookingService->create($bookingRequest);
            $this->fail('Booking is created with expired coupon');
        } catch (ExpiredCouponException $exception) {
            $this->assertTrue(true);
        }


    }

    public function test_calc_cost_for_booking_with_tour_options()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 36;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,

        ])->create();
        $tourOption = TourOption::factory()->create();
        $tour->options()->attach($tourOption);
        $user_cart = $cartService->getCart();
        $adults = 1;
        $children = 1;
        $infants = 3;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'options' => [$tourOption->id]
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $coupon = Coupon::factory([
            'start_date' => null,
            'end_date' => null,
            'active' => true,
            'limit_per_customer' => null,
            'limit_per_usage' => null,
        ])->create();

        $bookingRequest = Booking::factory([
            'coupon_id' => $coupon->id
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $sub_total_price = ($adult_price * $adults) + ($child_price * $children) + ($infant_price * $infants);
        $sub_total_price += ($tourOption->adult_price * $adults) + ($tourOption->child_price * $children);
        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->sub_total_price, 3));

        $total_price = $coupon->apply($booking->sub_total_price);
        $this->assertEquals(number_format($total_price, 3), number_format($booking->total_price, 3));

    }

    public function test_calculate_total_price_of_booking_with_group_priced_tours_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 10;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
            'pricing_groups' =>  [
                ['from' => 2, 'to'=> 4, 'price' => 90, 'child_price' => 40],
                ['from' => 5, 'to'=> 10, 'price' => 80, 'child_price' => 35],
            ]
        ])->create();
        $user_cart = $cartService->getCart();
        $adults = $children = 3;
        $infants = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $bookingRequest = Booking::factory([
            'coupon_id' => null
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $sub_total_price = (90 * $adults) + (40 * $children) + ($infant_price * $infants);
        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->sub_total_price, 3));
    }

    public function test_calculate_total_price_of_booking_with_tour_seasons_successfully()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 10;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
            'pricing_groups' =>  [
                ['from' => 2, 'to'=> 4, 'price' => 90, 'child_price' => 40],
                ['from' => 5, 'to'=> 10, 'price' => 80, 'child_price' => 35],
            ]
        ])->create();
        $season = TourSeason::factory([
            'start_day' => now()->subDay()->day,
            'start_month' => now()->subDay()->month,
            'end_day' => now()->addDay()->day,
            'end_month' => now()->addDay()->month,
            'pricing_groups' =>  [
                ['from' => 2, 'to'=> 4, 'price' => 50, 'child_price' => 5],
                ['from' => 5, 'to'=> 10, 'price' => 40, 'child_price' => 5],
            ]
        ])->make();

        $tour->seasons()->create($season->toArray());

        $user_cart = $cartService->getCart();
        $adults = $children = 5;
        $infants = 1;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $bookingRequest = Booking::factory([
            'coupon_id' => null
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();
        $sub_total_price = (40 * $adults) + (5 * $children) + ($infant_price * $infants);
        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->sub_total_price, 3));
        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->total_price, 3));
    }

    public function test_calc_cost_for_booking_with_tour_options_with_pricing_groups()
    {
        $adult_price = 100;
        $child_price = 50;
        $infant_price = 36;
        $cartService = new Cart();
        $tour = Tour::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'infant_price' => $infant_price,
            'pricing_groups' =>  [
                ['from' => 2, 'to'=> 4, 'price' => 90, 'child_price' => 40],
                ['from' => 5, 'to'=> 10, 'price' => 80, 'child_price' => 35],
            ]
        ])->create();
        $tourOption = TourOption::factory([
            'adult_price' => $adult_price,
            'child_price' => $child_price,
            'pricing_groups' =>  [
                ['from' => 2, 'to'=> 4, 'price' => 90, 'child_price' => 40],
                ['from' => 5, 'to'=> 10, 'price' => 80, 'child_price' => 35],
            ]
        ])->create();
        $tour->options()->attach($tourOption);
        $user_cart = $cartService->getCart();
        $adults = 2;
        $children = 2;
        $infants = 3;
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'options' => [$tourOption->id]
        ])->make()->toArray();
        $cartService->appendTour($cartItem);

        $coupon = Coupon::factory([
            'start_date' => null,
            'end_date' => null,
            'active' => true,
            'limit_per_customer' => null,
            'limit_per_usage' => null,
        ])->create();

        $bookingRequest = Booking::factory([
            'coupon_id' => $coupon->id
        ])->make()->toArray();

        $bookingService = new BookingService;
        $bookingService->create($bookingRequest);

        $booking = $bookingService->getBooking();
        $booking->refresh();

        $sub_total_price = ($tour->calcAdultPrice($adults) * $adults) + ($tour->calcChildPrice($adults) * $children) + ($infant_price * $infants);

        $sub_total_price += ($tourOption->calcAdultPrice($adults) * $adults) + ($tourOption->calcChildPrice($adults) * $children);

        $this->assertEquals(number_format($sub_total_price, 3), number_format($booking->sub_total_price, 3));

        $total_price = $coupon->apply($booking->sub_total_price);
        $this->assertEquals(number_format($total_price, 3), number_format($booking->total_price, 3));

    }
}

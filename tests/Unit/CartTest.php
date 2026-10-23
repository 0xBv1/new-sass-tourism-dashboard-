<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Tour;
use App\Services\Client\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_get_user_cart_successfully()
    {
        $cartService = new Cart();
        $user_cart = $cartService->getCart();
        $this->assertEquals(auth('client')->id(), $user_cart->client_id);
    }

    public function test_add_to_cart_valid_tour()
    {
        $cartService = new Cart();
        $tour = Tour::factory()->create();
        $user_cart = $cartService->getCart();
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $this->assertCount(1, $user_cart->items->toArray());
    }

    public function test_remove_tour_from_cart_successfully()
    {
        $cartService = new Cart();
        $tour = Tour::factory()->create();
        $user_cart = $cartService->getCart();
        $cartItem = CartItem::factory([
            'tour_id' => $tour->id,
            'cart_id' => $user_cart->id
        ])->make()->toArray();
        $cartService->appendTour($cartItem);
        $this->assertCount(1, $user_cart->items->toArray());
        $cartService->remove($tour->id);
        $cartService->load();
        $this->assertCount(0, $cartService->getCart()->items()->get()->toArray());
        $this->assertCount(0, $cartService->items->toArray());
    }
}

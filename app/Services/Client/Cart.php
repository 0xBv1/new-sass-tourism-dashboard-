<?php

namespace App\Services\Client;

use App\Exceptions\InvalidTourStartDateException;
use App\Exceptions\NoAvailableCarForRouteException;
use App\Exceptions\NoCarRouteAvailable;
use App\Exceptions\TourIsNotAvailableAtDateException;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Models\CartRental;
use App\Models\Tour;
use App\Services\Client\CarRental as CarRentalService;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class Cart
{
    public Collection|CartItem|CartRental|array|null $items = null;
    private CartModel|null $cart;

    public function __construct()
    {
        $this->cart = auth('client')->check() ?
            auth('client')->user()->cart()->firstOrCreate() :
            CartModel::guest();
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function load(): Collection|array
    {
        if (!$this->items) {
            $this->items = $this->cart?->items ?? collect();
            $this->items = $this->items->merge($this->cart?->rentals ?? collect());
        }
        return $this->items;
    }

    /**
     * @throws Throwable
     */
    public function validate(): bool
    {
        $this->load();

        foreach ($this->items as $item) {
            throw_if(
                condition: $item->start_date->lt(now()),
                exception: new InvalidTourStartDateException($item->tour->title));
        }
        return true;
    }

    public function clear(): void
    {
        $this->cart->delete();
    }

    /**
     * @throws TourIsNotAvailableAtDateException|Throwable
     */
    public function appendTour(array $item): void
    {
        $tour = Tour::find($item['tour_id']);

        $date = $item['start_date'];
        $request_locale = app()->getLocale();

        //handle carbon localization and set to en as availability saved in english
        app()->setLocale('en');
        $tour_available =$tour->isAvailableAtDate($date);
        app()->setLocale($request_locale);

        throw_if(!$tour_available, new TourIsNotAvailableAtDateException($date));

        $this->cart->items()->updateOrCreate(
            attributes: collect($item)->only(['tour_id'])->toArray(),
            values: $item
        );
    }

    /**
     * @throws Throwable
     */
    public function appendRental(array $item): void
    {
        $carRentalService = new CarRentalService;
        $carRoute = $carRentalService->search($item['pickup_location_id'], $item['destination_id']);

        throw_if(!$carRoute, new NoCarRouteAvailable);

        $carRentalService->validateStops($item['stops'], $carRoute);

        $priceGroup = $carRentalService->searchForSuitableCar(
            $item['adults'] ?? 0,
            $item['children'] ?? 0,
            $carRoute);

        throw_if(!$priceGroup, new NoAvailableCarForRouteException);

        $item['car_route_price'] = $item['oneway'] ? $priceGroup->oneway_price : $priceGroup->rounded_price;
        $item['car_type'] = $priceGroup->car_type;

        $item['stops'] = $carRoute->stops->whereIn('stop_location_id', $item['stops'])->map(fn($item)=>[
            'id' => $item?->location?->id,
            'name' => $item?->location?->name,
            'price' => $item?->price,
        ]);

        $this->cart->rentals()->create($item);
    }

    public function remove($item): void
    {
        $this->cart->items()->where('tour_id', $item)->delete();
        $this->cart->rentals()->where('id', $item)->delete();
        if ($this->cart->items()->count() == 0 && $this->cart->rentals()->count() == 0) {
            $this->cart->delete();
            $this->items = new Collection();
        }
    }
}

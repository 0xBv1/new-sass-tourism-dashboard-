<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\CarRental;
use App\Payments\Gateways\Card;
use App\Events\NewCarRentalEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use App\Services\Recaptcha\RecaptchaService;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\NoAvailableCarForRouteException;
use App\Http\Requests\Api\CarRental\CheckoutRequest;
use App\Services\Client\CarRental as CarRentalService;
use App\Http\Requests\Api\CarRental\SearchForRouteRequest;
use App\Http\Requests\Api\CarRental\AvailableDestinationsRequest;

class CarRentalController extends Controller
{
    use HasApiResponse;
    protected $recaptchaService;

    public function __construct(
        protected CarRentalService $carRentalService,
        RecaptchaService $recaptchaService
    ){
        $this->recaptchaService = $recaptchaService;
     }

    public function searchForAvailableDestinations(AvailableDestinationsRequest $request)
    {
        return $this->send(
            $this->carRentalService->availableDestinations($request->get('pickup_location_id'))
        );
    }


    public function searchForRoute(SearchForRouteRequest $request)
    {
        $carRoute = $this->carRentalService->search($request->get('pickup_location_id'), $request->get('destination_id'));

        if (!$carRoute) {
            return $this->send(message: __('messages.car-rental.not-found'), statusCode: Response::HTTP_NOT_FOUND);
        }

        return $this->send($carRoute, __('messages.car-rental.found'));
    }

    /**
     * @throws \Throwable
     */
    public function checkout(CheckoutRequest $request)
    {
        try {
            $carRoute = $this->carRentalService->search($request->get('pickup_location_id'), $request->get('destination_id'));

            if (!$carRoute) {
                return $this->send(message: __('messages.car-rental.not-found'), statusCode: Response::HTTP_NOT_FOUND);
            }

            $this->carRentalService->validateStops($request->get('stops'), $carRoute);

            $priceGroup = $this->carRentalService->searchForSuitableCar(
                $request->get('adults', 0),
                $request->get('children', 0),
                $carRoute);


            if (!$priceGroup) {
                throw new NoAvailableCarForRouteException();
            }

            $rentalInfo = $request->getSanitized();
            $rentalInfo['car_route_price'] = $request->get('oneway') ? $priceGroup->oneway_price : $priceGroup->rounded_price;
            $rentalInfo['car_type'] = $priceGroup->car_type;

            $carRental = CarRental::create($rentalInfo);

            foreach ($request->get('stops', []) as $stopId) {
                $carRental->stops()->create([
                    'stop_location_id' => $stopId,
                    'price' => $carRoute->stops->firstWhere('stop_location_id', $stopId)->price
                ]);
            }

            return $this->send(
                message: 'Car rental request sent successfully. Will get you back ASAP!',
                statusCode: Response::HTTP_CREATED
            );
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            return $this->send(
                message: __('messages.bookings.error') . $exception->getMessage(),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }
    }
}

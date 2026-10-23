<?php

namespace App\Http\Controllers\Api;

use App\Events\NewBookingEvent;
use App\Exceptions\CouponNotAvailableForSelectedToursException;
use App\Exceptions\EmptyCartException;
use App\Exceptions\ExpiredCouponException;
use App\Exceptions\InActiveCouponException;
use App\Exceptions\InvalidPaymentGateWayException;
use App\Exceptions\InvalidStopLocationException;
use App\Exceptions\LoginFirstToUseCouponException;
use App\Exceptions\NoAvailableCarForRouteException;
use App\Exceptions\UsedCouponException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Booking\BookingRequest;
use App\Http\Resources\Api\BookingResource;
use App\Models\Booking;
use App\Payments\PaymentGateway;
use App\Services\Client\Booking as BookingService;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BookingController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Get user bookings",
     *     description="Returns paginated list of authenticated client's bookings",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Booking, $request);
        $bookings = $queryBuilder->build()->where('client_id', auth()->id())->paginate();
        $collection = BookingResource::collection($bookings->getCollection());
        $bookings->setCollection(collect($collection));
        return $this->send($bookings);
    }

    /**
     * @OA\Get(
     *     path="/api/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Get booking details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Booking not found")
     * )
     */
    public function show(Request $request, mixed $id)
    {
        $queryBuilder = new QueryBuilder(new Booking, $request);
        $booking = $queryBuilder->build()->find($id);
        return $this->send(new BookingResource($booking));
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Create a new booking",
     *     description="Create a booking from the shopping cart",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "phone", "payment_method"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="payment_method", type="string", enum={"cod", "paypal", "fawaterk"}),
     *             @OA\Property(property="coupon_code", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Booking created successfully"),
     *     @OA\Response(response=400, description="Cart empty or invalid coupon")
     * )
     */
    public function create(BookingRequest $request, BookingService $bookingService)
    {
        DB::beginTransaction();
        try {
            $bookingService->create($request->getSanitized());

            $booking = $bookingService->getBooking();

            NewBookingEvent::dispatchIf($booking->isCod(), $booking);

            DB::commit();

            $booking->load('tours', 'rentals', 'client', 'coupon');

//            $gateway->pay($booking);

            return $this->send(
                data: [
                    'booking' => new BookingResource($booking),
//                    'payment' => [
//                        'redirect' => $gateway->redirect(),
//                        'message' => $gateway->message()
//                    ]
                ],
                message: __('messages.bookings.created'),
                statusCode: Response::HTTP_CREATED
            );
        } catch (InActiveCouponException|
        ExpiredCouponException|
        UsedCouponException|
        EmptyCartException|
        InvalidPaymentGateWayException|
        InvalidStopLocationException|
        LoginFirstToUseCouponException|
        NoAvailableCarForRouteException|
        CouponNotAvailableForSelectedToursException $exception) {
            DB::rollBack();
            return $this->send(
                message: $exception->getMessage(),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        } catch (Throwable|Exception $exception) {
            DB::rollBack();
            report($exception);
            return $this->send(
                data: [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                ],
                message: __('messages.bookings.error'),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }
    }
}

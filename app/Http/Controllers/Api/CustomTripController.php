<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomTrip;
use App\Services\Recaptcha\RecaptchaService;
use App\Http\Controllers\Controller;
use App\Traits\Response\HasApiResponse;
use App\Events\NewCustomTripRequestEvent;
use App\Http\Requests\Api\CustomTripRequest;
use Symfony\Component\HttpFoundation\Response;

class CustomTripController extends Controller
{
    use HasApiResponse;
    protected $recaptchaService;

    /**
     * Inject the RecaptchaService via constructor.
     *
     * @param RecaptchaService $recaptchaService
     */
    public function __construct(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
    }

    public function __invoke(CustomTripRequest $request)
    {
        // Verify the reCAPTCHA token using the service
//        $recaptchaData = $this->recaptchaService->verify($request->recaptcha_token, $request->ip());
//
//        if (!$recaptchaData['success'] || $recaptchaData['score'] < 0.5) {
//            Log::warning('reCAPTCHA validation failed', ['response' => $recaptchaData]);
//            return $this->send(
//                message: __('messages.custom-trips.failed_captcha'),
//                statusCode: Response::HTTP_BAD_REQUEST
//            );
//        }

        $customTrip = CustomTrip::create($request->getSanitized());

        if ($request->get('categories')) {
            $customTrip->categories()->attach($request->get('categories'));
        }

        event(new NewCustomTripRequestEvent($customTrip));

        $customTrip->refresh();

        return $this->send(
            data: $customTrip->toArray(),
            message: __('messages.custom-trips.created'),
            statusCode: Response::HTTP_CREATED
        );
    }
}

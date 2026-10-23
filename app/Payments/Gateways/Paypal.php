<?php

namespace App\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Exceptions\PaymentErrorException;
use App\Models\Booking;
use App\Payments\PaymentGateway;
use App\Payments\PaymentVerify;
use Exception;
use Psr\Http\Message\StreamInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class Paypal implements PaymentGateway, PaymentVerify
{

    private PayPalClient $client;
    private Booking $booking;
    /**
     * @var array|mixed
     */
    private mixed $gateway_invoice_response;

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function __construct()
    {
        $this->client = new PayPalClient;
        $this->client->setApiCredentials(config('paypal'));
        $this->client->getAccessToken();
    }

    /**
     * @param Booking $booking
     * @throws Throwable
     */
    public function pay(Booking $booking): void
    {
        $this->setBooking($booking);

        $total_price = $this->booking->total_price * $this->booking->currency_exchange_rate;

        $total_price = round($total_price, 2);

        $response = $this->client->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => site_url('/order/payment/callback/paypal/verify'),
                "cancel_url" => site_url('/order/payment/callback/paypal/canceled'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => strtoupper($this->booking->currency->name),
                        "value" => $total_price
                    ]
                ]
            ]
        ]);

        $this->booking->payment()->create([
            'invoice_id' => $response['id'],
            'gateway' => PaymentMethod::PAYPAL->value,
            'transaction_request' => $response,
        ]);

        throw_unless(isset($response['id']) && $response['id'] != null,
            new PaymentErrorException(__('messages.payment.paypal-failed')));


        $this->gateway_invoice_response = collect($response['links'])->firstWhere('rel', 'approve');

    }

    public function redirect(): array
    {
        return [
            'type' => 'new-page',
            'location' => $this->gateway_invoice_response['href'] ?? site_url('/order/payment/callback/paypal/cancel')
        ];
    }

    public function message(): string
    {
        return __('messages.bookings.payment-redirect');
    }

    /**
     * @throws Throwable
     */
    public function verify(array $options = []): StreamInterface|array|string
    {
        return $this->client->capturePaymentOrder($options['token']);
    }

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;
        return $this;
    }
}

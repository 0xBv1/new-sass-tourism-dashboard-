<?php

namespace App\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Exceptions\PaymentErrorException;
use App\Models\Booking;
use App\Payments\PaymentGateway;
use App\Payments\PaymentVerify;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Card implements PaymentGateway, PaymentVerify
{
    private PendingRequest $http;
    private Booking $booking;
    /**
     * @var array|mixed
     */
    private mixed $gateway_invoice_response;
    private array $transaction_payload;

    public function __construct()
    {
        $apiKey = config('fawaterak.api_key');

        $this->http = Http::baseUrl(config('fawaterak.base_url'))
            ->contentType('application/json')
            ->withHeaders([
                'Authorization' => "Bearer $apiKey"
            ]);
    }

    public function listMethods(): Collection
    {
        return $this->http->get('api/v2/getPaymentmethods')
            ->collect('data')
            ->filter(fn($method) => str($method['name_en'])->lower()->contains('visa'));
    }

    private function prepare(): self
    {
        $total_price = $this->booking->total_price * $this->booking->currency_exchange_rate;

        $total_price = round($total_price, 2);

        $this->transaction_payload =   [
            'payment_method_id' => request('payment_method_id', 2),
            'cartTotal' => $total_price,
            'currency' => $this->booking->currency->name,
            'customer' => [
                'first_name' => $this->booking->first_name ?? 'N/A',
                'last_name' => $this->booking->last_name ?? 'N/A',
                'email' => $this->booking->email ?? 'N/A',
                'phone' => $this->booking->phone ?? 'N/A',
                'address' => 'N/A',
            ],
            'redirectionUrls' => [
                "successUrl" => site_url('/order/payment/callback/fawaterk/success'),
                "failUrl" => site_url('/order/payment/callback/fawaterk/canceled'),
                "pendingUrl" => site_url('/order/payment/callback/fawaterk/pending')
            ],
            'cartItems' => [
                [
                    'name' => 'Booking Details',
                    'price' => $total_price,
                    'quantity' => 1,
                ]
            ]
        ];

        return $this;
    }

    private function transaction(): PromiseInterface|Response
    {
        return $this->http->post('api/v2/invoiceInitPay', $this->transaction_payload);
    }

    /**
     * @throws PaymentErrorException
     */
    public function pay(Booking $booking): void
    {
        $response = $this->setBooking($booking)
            ->prepare()
            ->transaction();

        if (($response->json()['status'] ?? '') == "error") {
            throw new PaymentErrorException($response->collect('message')->first());
        }

        $this->gateway_invoice_response = $response->json('data');
        $this->gateway_invoice_response['invoice_id'] = str($this->gateway_invoice_response['invoice_id'])->toString();

        $this->booking->payment()->create([
            'invoice_id' => $this->gateway_invoice_response['invoice_id'],
            'gateway' => PaymentMethod::CARD->value,
            'transaction_request' => $this->gateway_invoice_response,
        ]);
    }

    public function redirect(): array
    {
        return [
            'type' => 'new-page',
            'location' => $this->gateway_invoice_response['payment_data']['redirectTo']
        ];
    }

    public function message(): string
    {
        return __('messages.bookings.payment-redirect');
    }

    public function verify(array $options = [])
    {
        return $this->http->get('api/v2/getInvoiceData/' . $options['invoice_id'])->json('data');
    }

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;
        return $this;
    }
}

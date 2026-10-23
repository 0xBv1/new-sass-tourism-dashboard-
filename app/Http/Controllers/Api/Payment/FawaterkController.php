<?php

namespace App\Http\Controllers\Api\Payment;

use App\Enums\PaymentStatus;
use App\Events\NewBookingEvent;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Payments\Gateways\Card;
use App\Traits\Response\HasApiResponse;
use Symfony\Component\HttpFoundation\Response;

class FawaterkController extends Controller
{
    use HasApiResponse;

    public function methods(Card $paymentGateWay)
    {
        $methods = $paymentGateWay->listMethods()->map(fn($method) => [
            'paymentId' => $method['paymentId'],
            'name' => $method['name_en'],
            'redirect' => $method['redirect'],
            'logo' => $method['logo'],
        ]);
        return $this->send(data: $methods);
    }

    public function updateInvoice(Card $paymentGateWay)
    {
        $payment = Payment::with('booking')->whereInvoiceId(request('invoice_id'))->first();

        if (!$payment || !$payment?->booking) {
            return $this->send(message: __('messages.booking-not-found'), statusCode: Response::HTTP_NOT_FOUND);
        }

        $already_verified = !is_null($payment->transaction_verification);

        if (!$payment->transaction_verification) {
            $payment->fill([
                'transaction_verification' => $paymentGateWay->verify(request()->only('invoice_id'))
            ])->save();
        }

        $status = $payment->transaction_verification['status_text'] == 'paid' && $payment->transaction_verification['paid'] == 1 ?
            PaymentStatus::PAID->value : PaymentStatus::NOT_PAID->value;

        $payment->booking->update(['payment_status' => $status]);

        if (!$already_verified) {
            event(new NewBookingEvent($payment->booking));
        }

        return $this->send(message: __('messages.bookings.payment_verified'));
    }
}

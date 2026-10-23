<?php

namespace App\Http\Controllers\Api\Payment;

use App\Enums\PaymentStatus;
use App\Events\NewBookingEvent;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Payments\Gateways\Paypal;
use App\Traits\Response\HasApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaypalController extends Controller
{
    use HasApiResponse;

    /**
     * @throws Throwable
     */
    public function capture(Paypal $paymentGateWay)
    {
        $invoice_id = request('invoice_id');

        $payment = Payment::with('booking')->whereInvoiceId($invoice_id)->first();

        if (!$payment || !$payment?->booking) {
            return $this->send(message: __('messages.booking-not-found'), statusCode: Response::HTTP_NOT_FOUND);
        }

        $already_verified = !is_null($payment->transaction_verification);

        $payment_verification = $payment->transaction_verification ?? $paymentGateWay->verify(['token' => $invoice_id]);

        if (!$already_verified) {
            $payment->fill([
                'transaction_verification' => $payment_verification
            ])->save();
        }

        $status = isset($payment_verification['status']) && $payment_verification['status'] == 'COMPLETED' ? PaymentStatus::PAID->value : PaymentStatus::NOT_PAID->value;

        if (!$already_verified) {
            $payment->booking->update(['payment_status' => $status]);

            $payment->booking->refresh();
        }

        if ($status == PaymentStatus::PAID->value) {
            if (!$already_verified) {
                event(new NewBookingEvent($payment->booking));
            }
            return $this->send(data: $payment_verification, message: __('messages.bookings.payment_verified'));
        }

        return $this->send(data: $payment_verification, message: __('messages.payment.paypal-not-captured'), statusCode: Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws Throwable
     */
    public function cancel(Paypal $paymentGateWay)
    {
        $invoice_id = request('invoice_id');

        $payment = Payment::with('booking')->whereInvoiceId($invoice_id)->first();

        $already_verified = !is_null($payment->transaction_verification);

        if (!$payment || !$payment?->booking) {
            return $this->send(message: __('messages.booking-not-found'), statusCode: Response::HTTP_NOT_FOUND);
        }
        $payment_verification = $payment->transaction_verification ?? $paymentGateWay->verify(['token' => $invoice_id]);

        $payment->fill([
            'transaction_verification' => array_merge($payment_verification, ['payment_status' => 'CANCELED'])
        ])->save();

        $payment->booking->update(['payment_status' => PaymentStatus::NOT_PAID->value]);

        if (!$already_verified) {
            event(new NewBookingEvent($payment->booking));
        }
        
        return $this->send(message: __('messages.payment.canceled'), statusCode: Response::HTTP_OK);
    }
}

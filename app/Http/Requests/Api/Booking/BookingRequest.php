<?php

namespace App\Http\Requests\Api\Booking;

use App\Enums\PaymentMethod;
use App\Models\Currency;
use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'phone' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'pickup_location' => ['nullable', 'string', 'min:1', 'max:255'],
            'country' => ['required', 'string', 'min:1', 'max:255'],
            'state' => ['nullable', 'string', 'min:1', 'max:255'],
            'street_address' => ['nullable', 'string', 'min:1', 'max:255'],
            'payment_method' => ['nullable', 'string', Rule::in(PaymentMethod::all())],
            'coupon_id' => ['nullable', 'integer', 'exists:coupons,id'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'notes' => ['nullable', 'string', 'max:500'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function getSanitized(): array
    {
        $data = $this->validated();

        if (auth('client')->check()) {
            $data['client_id'] = auth('client')->id();
        }

        $data['currency_exchange_rate'] = Currency::find($this->get('currency_id'))->exchange_rate;
        $data['sub_total_price'] = 0;
        $data['total_price'] = 0;
        $data['payment_method'] = $this->get('payment_method', PaymentMethod::COD->value);

        return $data;
    }
}

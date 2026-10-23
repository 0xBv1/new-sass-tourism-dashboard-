<?php

namespace App\Http\Requests\Api\CarRental;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class AvailableDestinationsRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
        ];
    }
}

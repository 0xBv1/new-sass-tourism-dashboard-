<?php

namespace App\Http\Requests\Api\Booking;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class SaveDealRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'questions' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function getSanitized(): array
    {
        $data = $this->validated();
        $data['children'] = $data['children'] ?? 0;
        return $data;
    }
}



<?php

namespace App\Http\Requests\Api\Cart;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class AddCartTourRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'tour_id' => ['required', 'integer','exists:tours,id,deleted_at,NULL'],
            'start_date' => ['required', 'date','after_or_equal:today'],
            'options' => ['nullable', 'array'],
            'options.*' => ['integer', 'exists:tour_options,id'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
        ];
    }

    public function getSanitized()
    {
        return $this->validated();
    }
}

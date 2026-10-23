<?php

namespace App\Http\Requests\Api;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class TourReviewRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'rate' => ['required', 'numeric', 'min:0', 'max:5'],
            'content' => ['required', 'string', 'min:1', 'max:500'],
            'tour_id' => ['required', 'integer', 'exists:tours,id'],
            'reviewer_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function getSanitized()
    {
        return $this->validated();
    }
}

<?php

namespace App\Http\Requests\Api;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use RequestValidationErrorResponse;
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:clients'],
            'password' => ['required']
        ];
    }

    public function getSanitized()
    {
        return $this->validated();
    }
}

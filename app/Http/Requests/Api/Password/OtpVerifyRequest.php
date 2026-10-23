<?php

namespace App\Http\Requests\Api\Password;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest
{
    use RequestValidationErrorResponse;
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:password_resets', 'exists:clients,email,deleted_at,NULL'],
            'otp' => ['required'],
        ];
    }

}

<?php

namespace App\Http\Requests\Api\Password;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{

    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:clients,email,deleted_at,NULL']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

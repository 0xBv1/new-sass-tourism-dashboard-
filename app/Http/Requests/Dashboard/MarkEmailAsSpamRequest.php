<?php

namespace App\Http\Requests\Dashboard;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class MarkEmailAsSpamRequest extends FormRequest
{
    use RequestValidationErrorResponse;
    public function rules(): array
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

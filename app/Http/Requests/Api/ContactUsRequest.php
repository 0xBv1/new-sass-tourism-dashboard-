<?php

namespace App\Http\Requests\Api;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    use RequestValidationErrorResponse;
    public function rules(): array
    {
        return [
            'name' => ['required', 'string' , 'min:1'],
            'subject' => ['required', 'string' , 'min:1'],
            'email' => ['required', 'email' , 'min:1'],
            'phone' => ['required', 'string' , 'min:1'],
            'country' => ['required', 'string' , 'min:1'],
            'message' => ['required', 'string' , 'min:1'],
            //'recaptcha_token' => ['required', 'string' ],
        ];
    }

    public function getSanitized()
    {
        $data = $this->validated();
        $data['ip'] = $this->ip();
        return $data;
    }
}

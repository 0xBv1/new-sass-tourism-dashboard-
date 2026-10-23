<?php

namespace App\Http\Requests\Api;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255','min:2'],
            'email' => ['required', 'email', 'unique:clients'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['nullable', 'date', 'before:today', 'date_format:Y-m-d'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100']
        ];
    }

    public function getSanitized(): array
    {
        return array_merge($this->validated(), [
            'password' => Hash::make($this->get('password'))
        ]);
    }
}

<?php

namespace App\Http\Requests\Api\Profile;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['nullable', 'date', 'before:today', 'date_format:Y-m-d'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100']
        ];
    }

    public function getSanitized()
    {
        $data = $this->validated();
        unset($data['password']);
        if ($this->filled('password')) {
            $data['password'] = Hash::make($this->get('password'));
        }
        return $data;
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RoomAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'city' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'check_in.required' => 'Check-in date is required',
            'check_in.after_or_equal' => 'Check-in date cannot be in the past',
            'check_out.required' => 'Check-out date is required',
            'check_out.after' => 'Check-out date must be after check-in date',
            'city.required' => 'City is required',
        ];
    }

    /**
     * Get sanitized input data.
     */
    public function getSanitized(): array
    {
        return [
            'check_in' => $this->input('check_in'),
            'check_out' => $this->input('check_out'),
            'city' => $this->input('city'),
        ];
    }
}

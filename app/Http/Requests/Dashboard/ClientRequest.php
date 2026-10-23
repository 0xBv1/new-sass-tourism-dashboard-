<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            "name" => "Name",
            "email" => "Email",
            "password" => "Password",
            "phone" => "Phone",
            "nationality" => "Nationality",
            "birthdate" => "Birthdate",
            "blocked" => "Blocked",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('clients')->ignore(request('client'))],
            'password' => ['sometimes'],
            'phone' => ['nullable', 'max:20'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'blocked' => ['nullable'],
        ];
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $data = $this->validated();
        $data['blocked'] = $this->filled('blocked');
        unset($data['password']);
        if ($this->filled('password')) {
            $data['password'] = \Hash::make($this->get('password'));
        }
        return $data;
    }
}

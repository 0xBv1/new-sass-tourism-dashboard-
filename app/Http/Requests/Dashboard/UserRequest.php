<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            "role" => "Role",
            "phone" => "Phone",
            "phone_code" => "Phone Code",
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
            'email' => ['required', 'email', Rule::unique('users')->ignore(request('user'))],
            'password' => $this->isMethod('POST') ? ['required', 'min:8'] : ['nullable'],
            'roles' => ['required', 'array', 'min:1'],
            'phone' => ['required', 'string', 'max:11'],
            'phone_code' => ['required', 'string', 'exists:countries'],
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
        unset($data['roles'], $data['password']);
        if ($this->get('password')) {
            $data['password'] = \Hash::make($this->get('password'));
        }
        return $data;
    }
}

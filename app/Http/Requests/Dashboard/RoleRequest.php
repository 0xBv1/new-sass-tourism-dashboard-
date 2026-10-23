<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => 'Name',
            'permissions' => 'Permissions'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:200', Rule::unique('roles')->ignore(request('role'))],
            'permissions' => ['required', 'array'],
        ];
        if ($this->isMethod('PUT') && $this->get('name') == 'Administrator') {
            unset($rules['name']);
        }
        return $rules;
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
    public function getSanitized(): array
    {
        return $this->validated();
    }

    /**
     * Get the validated fields.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return collect(($this->get('permissions')))
            ->values()
            ->filter(fn($permission_id) => is_numeric($permission_id))
            ->toArray();
    }
}

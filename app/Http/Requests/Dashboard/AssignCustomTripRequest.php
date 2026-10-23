<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AssignCustomTripRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'assigned_operator_id' => ['required', 'integer', 'exists:users,id']
        ];
    }

    public function getSanitized(): array
    {
        return array_merge($this->validated(), [
            'assigned_by_id' => admin()->id,
            'assigned_at' => now(),
        ]);
    }
}

<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ImportCarRoutesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:csv,txt'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

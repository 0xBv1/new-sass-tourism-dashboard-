<?php

namespace App\Http\Requests\Platform;

use App\Models\Tenant;
use App\Services\Tenancy\TenantUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Stancl\Tenancy\Database\Models\Domain;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => strtolower((string) $this->input('slug')),
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'regex:/^[a-z][a-z0-9]{1,30}$/',
                'not_in:www,mail,ftp,admin,api,platform,localhost,app',
                Rule::unique(Tenant::class, 'id'),
            ],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $domain = TenantUrl::hostFor((string) $this->input('slug'));

            if (Domain::query()->where('domain', $domain)->exists()) {
                $validator->errors()->add('slug', 'هذا السب دومين مستخدم بالفعل.');
            }
        });
    }
}

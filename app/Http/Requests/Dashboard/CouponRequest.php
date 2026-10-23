<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\CouponType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
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
            "title" => "Title",
            "code" => "Code",
            "active" => "Active",
            "value" => "Value",
            "discount_type" => "Discount Type",
            "start_date" => "Start Date",
            "end_date" => "End Date",
            "limit_per_usage" => "Limit Per Usage",
            "limit_per_customer" => "Limit Per Customer",
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
            'title' => ['nullable', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:10', Rule::unique('coupons')->ignore(request('coupon'))],
            'active' => ['nullable'],
            'value' => array_merge(['required', 'min:1'],
                $this->get('discount_type') == CouponType::PERCENTAGE ? ['max:100'] : []),
            'discount_type' => ['required', Rule::in(CouponType::all())],
            'start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d','required_with:start_date', 'date', 'after_or_equal:start_date'],
            'limit_per_usage' => ['required'],
            'limit_per_customer' => ['required'],
            'tours' => ['nullable', 'array'],
            'tours.*' => ['integer', 'exists:tours,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
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
        $data['active'] = $this->filled('active');
        return $data;
    }
}

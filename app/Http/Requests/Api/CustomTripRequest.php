<?php

namespace App\Http\Requests\Api;

use App\Enums\CustomTripDestination;
use App\Enums\CustomTripType;
use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomTripRequest extends FormRequest
{
    use RequestValidationErrorResponse;

    public function rules(): array
    {
        $rules = [
            'destination' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(CustomTripType::all())],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'min_person_budget' => ['required', 'numeric', 'min:0'],
            'max_person_budget' => ['required', 'numeric', 'min:0'],
            'flight_offer' => ['required', 'boolean'],
            'additional_notes' => ['nullable', 'string', 'max:500'],
//            'categories' => ['required', 'array', 'min:1'],
//            'categories.*' => ['required', 'integer', 'exists:customized_trip_categories,id'],
          //'recaptcha_token' => ['required', 'string' ],
        ];
        switch ($this->get('type')):
            case CustomTripType::EXACT_TIME->value:
                $rules['start_date'] = ['required', 'date', 'after_or_equal:today'];
                $rules['end_date'] = ['required', 'date', 'after_or_equal:start_date'];
                break;
            case CustomTripType::APPROX_TIME->value:
                $rules['month'] = ['required', 'integer', 'min:1', 'max:12'
                ];
                // $rules['days'] = ['required', 'integer', 'min:1', 'max:31'];
                break;
            case CustomTripType::NOT_SURE->value:
                $rules['days'] = ['nullable', 'integer', 'min:1', 'max:31'];
                break;
        endswitch;
        return $rules;
    }

    public function getSanitized()
    {
        $data = $this->validated();
//        if (array_key_exists('month', $data)) {
//            $data['month'] += 1;
//        }
        unset($data['categories']);
        return $data;
    }
}

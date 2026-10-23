<?php

namespace App\Traits\Response;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait RequestValidationErrorResponse
{
    use HasApiResponse;

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->send(null, $validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

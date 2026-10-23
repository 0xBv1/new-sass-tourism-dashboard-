<?php

namespace App\Exceptions;

use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTourStartDateException extends HttpException
{
    use HasApiResponse;
    public function __construct($tourName = null)
    {
        $message = __('messages.cart.invalid_start_date', ['tour' => $tourName]);
        throw new HttpResponseException($this->send(null, $message, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}

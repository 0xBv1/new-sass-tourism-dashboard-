<?php

namespace App\Http\Middleware;

use App\Traits\Response\HasApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class SetApiLocaleMiddleware
{
    use HasApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('X-Localize', config('app.locale'));
        if (!in_array($locale, config('translatable.locales'))) {
            throw new HttpResponseException($this->send(message: "Invalid Language", statusCode: 400));
        }
        app()->setLocale($locale);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancyUnlessCentral
{
    public function handle(Request $request, Closure $next)
    {
        $central = config('tenancy.central_domains', []);

        if (in_array($request->getHost(), $central, true)) {
            return $next($request);
        }

        return app(InitializeTenancyByDomain::class)->handle($request, $next);
    }
}

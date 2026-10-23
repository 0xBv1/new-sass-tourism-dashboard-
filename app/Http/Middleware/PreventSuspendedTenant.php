<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventSuspendedTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant();

        if ($tenant && filled($tenant->suspended_at)) {
            return response()->view('tenant.suspended', [
                'company' => $tenant->company_name ?: $tenant->getTenantKey(),
            ], 403);
        }

        if ($tenant && $tenant->isOpeningSoon()) {
            return response()->view('tenant.waiting', [
                'company' => $tenant->company_name ?: $tenant->getTenantKey(),
                'opensAt' => $tenant->opensAt()->getTimestamp(),
            ]);
        }

        return $next($request);
    }
}

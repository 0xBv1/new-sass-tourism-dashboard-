<?php

namespace App\Http\Middleware;

use App\Services\InstallService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    protected InstallService $installService;

    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Always allow access to install routes - check this first
        if ($request->is('install*') || $request->path() === 'install') {
            return $next($request);
        }

        // Try to check if installed, but don't fail if there's an error
        try {
            // If not installed, redirect to installation
            if (!$this->installService->isInstalled()) {
                return redirect()->route('install.index');
            }
        } catch (\Exception $e) {
            // If there's any error (like missing .env or database), redirect to install
            return redirect()->route('install.index');
        }

        return $next($request);
    }
}

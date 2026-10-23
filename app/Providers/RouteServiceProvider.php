<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Middleware\PreventSuspendedTenant;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware([
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
                PreventSuspendedTenant::class,
                'api',
            ])->prefix('api')->group(base_path('routes/api.php'));

            $primary = (string) config('tenancy.primary_central_domain');
            $domains = array_values(array_unique(array_filter(array_merge(
                array_diff(config('tenancy.central_domains', []), [$primary]),
                [$primary]
            ))));

            $requestHost = $this->app->runningInConsole() ? '' : (string) request()->getHost();
            if ($requestHost !== '' && in_array($requestHost, $domains, true)) {
                $domains = array_values(array_diff($domains, [$requestHost]));
                $domains[] = $requestHost;
            }

            foreach ($domains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10000)->by($request->user()?->id ?: $request->ip());
        });
    }
}

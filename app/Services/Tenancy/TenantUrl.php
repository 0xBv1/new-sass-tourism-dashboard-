<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;

class TenantUrl
{
    public static function baseDomain(): string
    {
        $configured = (string) config('tenancy.base_domain');

        if ($configured !== '') {
            return $configured;
        }

        $central = (string) config('tenancy.primary_central_domain');

        if ($central !== '' && filter_var($central, FILTER_VALIDATE_IP) === false) {
            return $central;
        }

        return 'localhost';
    }

    public static function hostFor(string $slug): string
    {
        return $slug.'.'.static::baseDomain();
    }

    public static function urlFor(string $slug): string
    {
        return static::rootForHost(static::hostFor($slug));
    }

    public static function rootForTenant(?Tenant $tenant = null): string
    {
        $domain = $tenant?->domains()->first()?->domain;

        return static::rootForHost($domain ?: 'localhost');
    }

    public static function rootForHost(string $host): string
    {
        $base = (string) config('tenancy.app_url', config('app.url'));
        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'http';
        $port = parse_url($base, PHP_URL_PORT);

        return $scheme.'://'.$host.($port ? ':'.$port : '');
    }
}

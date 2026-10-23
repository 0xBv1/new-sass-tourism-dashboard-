<?php

declare(strict_types=1);

use App\Models\Tenant;

$centralDomain = env('CENTRAL_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost');

return [
    'tenant_model' => Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    'domain_model' => Stancl\Tenancy\Database\Models\Domain::class,

    'primary_central_domain' => $centralDomain,
    'base_domain' => env('TENANCY_BASE_DOMAIN', 'localhost'),
    'app_url' => env('APP_URL', 'http://localhost'),

    'central_domains' => array_values(array_unique(array_filter([
        $centralDomain,
        '127.0.0.1',
        'localhost',
    ]))),

    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // File cache is isolated because storage_path() is suffixed per company.
        // CacheTenancyBootstrapper needs a taggable store (redis/memcached) and breaks the file driver.
    ],

    'database' => [
        'central_connection' => 'central',
        'template_tenant_connection' => 'mysql',
        'prefix' => env('TENANCY_DB_PREFIX', 'tenant_'),
        'suffix' => '',
        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'mariadb' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    'cache' => [
        'tag_base' => 'tenant',
    ],

    'filesystem' => [
        'suffix_base' => 'tenant_',
        'disks' => [
            'local',
            'public',
            'media',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
            'media' => '%storage_path%/app/public/media/',
        ],
        'suffix_storage_path' => true,
        // Dashboard CSS lives in public/assets and must stay on the global asset() helper.
        // Company uploads are served from /storage by TenantStorageController.
        'asset_helper_tenancy' => false,
    ],

    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [],
    ],

    'features' => [],

    'routes' => true,

    'migration_parameters' => [
        '--force' => true,
        '--path' => [
            database_path('migrations'),
            base_path('vendor/timokoerber/laravel-one-time-operations/database/migrations'),
        ],
        '--realpath' => true,
    ],

    'seeder_parameters' => [
        '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
        '--force' => true,
    ],
];

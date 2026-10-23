<?php

namespace App\Console\Commands;

use App\Models\PlatformUser;
use App\Services\Tenancy\TenantStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PlatformInstallCommand extends Command
{
    protected $signature = 'platform:install';

    protected $description = 'Create the central platform database, its tables, and the platform admin';

    public function handle(): int
    {
        $database = (string) config('database.connections.central.database');

        if ($database === '') {
            $this->error('DB_CENTRAL_DATABASE is empty.');

            return self::FAILURE;
        }

        if (! $this->ensureCentralDatabase($database)) {
            return self::FAILURE;
        }

        Artisan::call('migrate', [
            '--database' => 'central',
            '--path' => 'database/migrations/central',
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        $this->ensurePassportKeys();
        $this->ensurePlatformAdmin();
        TenantStorage::detachSharedSymlink();
        app(\App\Services\InstallService::class)->markAsInstalled();

        $this->info('Platform is ready at http://'.config('tenancy.primary_central_domain').'/platform/login');

        return self::SUCCESS;
    }

    protected function ensureCentralDatabase(string $database): bool
    {
        try {
            DB::connection('central')->getPdo();
            $this->info("Central database `{$database}` is ready.");

            return true;
        } catch (\Throwable $exception) {
            if ($this->isAccessDenied($exception)) {
                $this->reportDatabaseAccessDenied($database);

                return false;
            }

            if (! $this->isUnknownDatabase($exception)) {
                throw $exception;
            }
        }

        try {
            $this->createDatabase($database);
        } catch (\Throwable $exception) {
            if ($this->isAccessDenied($exception)) {
                $this->error("Database `{$database}` is missing, and this MySQL user cannot create databases.");
                $this->reportDatabaseAccessDenied($database);

                return false;
            }

            throw $exception;
        }

        DB::purge('central');
        $this->info("Central database `{$database}` is ready.");

        return true;
    }

    protected function createDatabase(string $database): void
    {
        $server = config('database.connections.mysql');
        $server['database'] = null;
        config(['database.connections.platform_server' => $server]);
        DB::purge('platform_server');

        $charset = config('database.connections.central.charset', 'utf8mb4');
        $collation = config('database.connections.central.collation', 'utf8mb4_unicode_ci');

        DB::connection('platform_server')->statement(
            "CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET `{$charset}` COLLATE `{$collation}`"
        );
    }

    protected function reportDatabaseAccessDenied(string $database): void
    {
        $user = config('database.connections.central.username');
        $this->line("Create `{$database}` in the hosting panel and add user `{$user}` to it.");
        $this->line('Shared hosting usually prefixes the name, so DB_CENTRAL_DATABASE must be the full name shown in the panel.');
        $this->line('If the password is already correct, change DB_HOST from 127.0.0.1 to localhost and run this command again.');
    }

    protected function isAccessDenied(\Throwable $exception): bool
    {
        return str_contains($exception->getMessage(), '1045');
    }

    protected function isUnknownDatabase(\Throwable $exception): bool
    {
        return str_contains($exception->getMessage(), '1049')
            || str_contains($exception->getMessage(), 'Unknown database');
    }

    protected function ensurePassportKeys(): void
    {
        if (File::exists(base_path('storage/oauth-private.key'))) {
            return;
        }

        Artisan::call('passport:keys', ['--force' => true]);
        $this->line(Artisan::output());
    }

    protected function ensurePlatformAdmin(): void
    {
        $email = env('PLATFORM_ADMIN_EMAIL');
        $password = env('PLATFORM_ADMIN_PASSWORD');
        $name = env('PLATFORM_ADMIN_NAME', 'Platform Admin');

        if (! $email || ! $password) {
            $this->warn('Set PLATFORM_ADMIN_EMAIL and PLATFORM_ADMIN_PASSWORD to create the platform admin.');

            return;
        }

        PlatformUser::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'email' => $email, 'password' => $password]
        );

        $this->info("Platform admin {$email} is ready.");
    }
}

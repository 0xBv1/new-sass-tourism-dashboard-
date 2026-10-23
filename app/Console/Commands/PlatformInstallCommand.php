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

        $this->createDatabase($database);
        Artisan::call('migrate', [
            '--database' => 'central',
            '--path' => 'database/migrations/central',
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        $this->ensurePassportKeys();
        $this->ensurePlatformAdmin();
        TenantStorage::detachSharedSymlink();

        $this->info('Platform is ready at http://'.config('tenancy.primary_central_domain').'/platform/login');

        return self::SUCCESS;
    }

    protected function createDatabase(string $database): void
    {
        $server = config('database.connections.mysql');
        $server['database'] = 'mysql';
        config(['database.connections.platform_server' => $server]);

        $charset = config('database.connections.central.charset', 'utf8mb4');
        $collation = config('database.connections.central.collation', 'utf8mb4_unicode_ci');

        DB::connection('platform_server')->statement(
            "CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET `{$charset}` COLLATE `{$collation}`"
        );

        $this->info("Central database `{$database}` is ready.");
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

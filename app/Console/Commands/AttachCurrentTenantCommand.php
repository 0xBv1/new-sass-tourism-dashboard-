<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\Tenancy\LocalHosts;
use App\Services\Tenancy\TenantStorage;
use App\Services\Tenancy\TenantUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AttachCurrentTenantCommand extends Command
{
    protected $signature = 'tenants:attach-current {slug} {--name=}';

    protected $description = 'Register the existing database and public files as the first company without wiping them';

    public function handle(): int
    {
        $slug = strtolower((string) $this->argument('slug'));

        if (! preg_match('/^[a-z][a-z0-9]{1,30}$/', $slug)) {
            $this->error('Slug must be 2-31 letters or numbers and start with a letter.');

            return self::FAILURE;
        }

        if (Tenant::query()->whereKey($slug)->exists()) {
            $this->info("Company [{$slug}] is already attached.");

            return self::SUCCESS;
        }

        $database = (string) config('database.connections.mysql.database');

        if ($database === '') {
            $this->error('DB_DATABASE is empty. It must stay pointed at the existing company database.');

            return self::FAILURE;
        }

        $name = (string) ($this->option('name') ?: config('app.name') ?: $slug);
        $host = TenantUrl::hostFor($slug);

        $tenant = new Tenant([
            'id' => $slug,
            'company_name' => $name,
        ]);
        $tenant->setInternal('db_name', $database);
        $tenant->setInternal('create_database', false);
        $tenant->save();
        $tenant->createDomain($host);

        $this->movePublicFiles($slug);
        TenantStorage::ensureFrameworkDirectories(TenantStorage::directoryFor($slug));
        TenantStorage::detachSharedSymlink();

        $url = TenantUrl::urlFor($slug);
        $this->info("Attached database `{$database}` as {$url}");
        $this->line('Dashboard: '.$url.'/login');
        $this->line('API: '.$url.'/api');

        $hostsResult = app(LocalHosts::class)->sync();
        if ($hostsResult === 'written') {
            $this->info("Subdomain {$host} was added to the local hosts file.");
        } elseif ($hostsResult === 'failed') {
            $this->warn('The subdomain was not written to the hosts file. Run php artisan tenancy:sync-hosts as administrator.');
        }

        return self::SUCCESS;
    }

    protected function movePublicFiles(string $slug): void
    {
        $source = base_path('storage/app/public');
        $target = TenantStorage::directoryFor($slug).'/app/public';

        if (! File::isDirectory($source)) {
            File::ensureDirectoryExists($target);

            return;
        }

        if (File::isDirectory($target)) {
            $this->warn("Files were left in place because {$target} already exists.");

            return;
        }

        File::ensureDirectoryExists(dirname($target));
        File::moveDirectory($source, $target);
        File::ensureDirectoryExists($source);
        $this->info('Moved public files into the company storage folder.');
    }
}

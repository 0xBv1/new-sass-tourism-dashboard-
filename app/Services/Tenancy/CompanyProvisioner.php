<?php

namespace App\Services\Tenancy;

use App\Jobs\ProvisionTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Throwable;

class CompanyProvisioner
{
    public const BATCH = 12;

    public function steps(): array
    {
        return [
            'database' => 'إنشاء قاعدة البيانات',
            'migrate' => 'إنشاء الجداول',
            'setup' => 'حساب المدير والإعدادات والملفات',
            'hosts' => 'تسجيل السب دومين',
        ];
    }

    public function needsProvision(Tenant $tenant): bool
    {
        if ($tenant->provision_status === 'ready') {
            return false;
        }

        if (in_array($tenant->provision_status, ['pending', 'failed'], true)) {
            return true;
        }

        return filled($tenant->admin_password);
    }

    public function snapshot(Tenant $tenant): array
    {
        $this->prepare($tenant);
        $tenant->refresh();

        $labels = $this->steps();
        $ready = ! $this->needsProvision($tenant);
        $done = $ready ? array_keys($labels) : $this->done($tenant);
        $status = $ready ? 'ready' : ($tenant->provision_status ?: 'pending');
        $next = $ready ? null : $this->nextKey($done);
        $migrations = $this->migrationProgress($tenant, $done);

        $steps = [];
        foreach ($labels as $key => $label) {
            $state = 'waiting';
            if (in_array($key, $done, true)) {
                $state = 'done';
            } elseif ($key === $next && $status === 'failed') {
                $state = 'failed';
            } elseif ($key === $next) {
                $state = 'current';
            }

            $steps[] = [
                'key' => $key,
                'label' => $label,
                'state' => $state,
                'detail' => $key === 'migrate' ? $migrations['completed'].' من '.$migrations['total'] : null,
            ];
        }

        $setupDone = $ready || in_array('setup', $done, true);

        return [
            'status' => $status,
            'busy' => false,
            'company' => $tenant->company_name,
            'error' => $tenant->provision_error,
            'hosts' => $tenant->provision_hosts,
            'login_url' => $setupDone ? TenantUrl::urlFor($tenant->id).'/login' : null,
            'steps' => $steps,
        ];
    }

    public function advance(Tenant $tenant): array
    {
        $this->prepare($tenant);

        if (! $this->needsProvision($tenant)) {
            return $this->snapshot($tenant);
        }

        $lock = Cache::lock('company-provision:'.$tenant->id, 150);
        if (! $lock->get()) {
            $state = $this->snapshot($tenant);
            $state['busy'] = true;

            return $state;
        }

        try {
            set_time_limit(120);
            $this->runNext($tenant);
        } catch (Throwable $exception) {
            if (tenancy()->initialized) {
                tenancy()->end();
            }

            Log::error('Company provisioning step failed', [
                'tenant' => $tenant->id,
                'message' => $exception->getMessage(),
            ]);

            $tenant->refresh();
            $tenant->provision_status = 'failed';
            $tenant->provision_error = mb_substr($exception->getMessage(), 0, 500);
            $tenant->save();
        } finally {
            $lock->release();
        }

        return $this->snapshot($tenant);
    }

    protected function prepare(Tenant $tenant): void
    {
        if ($tenant->provision_status !== null || ! $this->needsProvision($tenant)) {
            return;
        }

        $done = [];
        if ($this->databaseExists($tenant)) {
            $done[] = 'database';
            if ($this->pendingMigrationFiles($tenant) === []) {
                $done[] = 'migrate';
            }
        }

        $tenant->defer_provisioning = true;
        $tenant->provision_status = 'pending';
        $tenant->provision_done = $done;
        $tenant->provision_error = null;
        $tenant->save();
    }

    protected function runNext(Tenant $tenant): void
    {
        $tenant->refresh();
        $next = $this->nextKey($this->done($tenant));

        if ($next === null) {
            $this->markReady($tenant);

            return;
        }

        $hostsResult = null;

        if ($next === 'database') {
            $this->createDatabase($tenant);
        } elseif ($next === 'migrate') {
            $this->migrateBatch($tenant);
        } elseif ($next === 'setup') {
            app()->call([new ProvisionTenant($tenant), 'handle']);
        } else {
            $this->ensureDomain($tenant);
            $hostsResult = app(LocalHosts::class)->sync();
        }

        if (tenancy()->initialized) {
            tenancy()->end();
        }

        $tenant->refresh();
        $done = $this->done($tenant);

        if ($next === 'database' || $next === 'setup') {
            $done[] = $next;
        }

        if ($next === 'migrate' && $this->pendingMigrationFiles($tenant) === []) {
            $done[] = 'migrate';
        }

        if ($next === 'hosts') {
            $tenant->provision_hosts = $hostsResult;
            if ($hostsResult === 'failed') {
                $tenant->provision_status = 'failed';
                $tenant->provision_error = 'الشركة اتجهزت. تسجيل السب دومين محتاج صلاحية ويندوز: اضغط إعادة المحاولة ووافق على النافذة، أو شغّل php artisan tenancy:sync-hosts كمسؤول.';
                $tenant->save();

                return;
            }

            $done[] = 'hosts';
        }

        $tenant->provision_done = array_values(array_unique($done));
        $tenant->provision_error = null;
        $tenant->provision_status = $this->nextKey($tenant->provision_done) === null ? 'ready' : 'pending';

        if ($tenant->provision_status === 'ready') {
            $tenant->defer_provisioning = false;
        }

        $tenant->save();
    }

    protected function markReady(Tenant $tenant): void
    {
        $tenant->provision_status = 'ready';
        $tenant->provision_done = array_keys($this->steps());
        $tenant->provision_error = null;
        $tenant->defer_provisioning = false;
        $tenant->save();
    }

    protected function ensureDomain(Tenant $tenant): void
    {
        $host = TenantUrl::hostFor($tenant->id);

        if ($tenant->domains()->where('domain', $host)->doesntExist()) {
            $tenant->createDomain($host);
        }
    }

    protected function createDatabase(Tenant $tenant): void
    {
        $expected = config('tenancy.database.prefix').$tenant->getTenantKey().config('tenancy.database.suffix');

        if ($tenant->databaseName() !== $expected) {
            $tenant->setInternal('db_name', $expected);
            $tenant->save();
        }

        if ($this->databaseExists($tenant)) {
            return;
        }

        try {
            app()->call([new CreateDatabase($tenant), 'handle']);
        } catch (Throwable $exception) {
            if (! str_contains($exception->getMessage(), '1044') && ! str_contains($exception->getMessage(), 'Access denied')) {
                throw $exception;
            }

            throw new RuntimeException(
                'الاستضافة لا تسمح بإنشاء قاعدة '.$tenant->databaseName().' تلقائياً. من MySQL Databases أنشئها وأضف المستخدم بصلاحية All Privileges، ثم أعد المحاولة.'
            );
        }
    }

    protected function migrateBatch(Tenant $tenant): void
    {
        foreach (array_slice($this->pendingMigrationFiles($tenant), 0, self::BATCH) as $file) {
            try {
                $this->migrateFile($tenant, $file);
            } catch (Throwable $exception) {
                if (! $this->isAlreadyApplied($exception)) {
                    throw $exception;
                }

                $this->markMigrationRan($tenant, pathinfo($file, PATHINFO_FILENAME));
                Log::warning('Marked an interrupted migration as finished', [
                    'tenant' => $tenant->id,
                    'migration' => pathinfo($file, PATHINFO_FILENAME),
                ]);
            }
        }
    }

    protected function migrateFile(Tenant $tenant, string $file): void
    {
        $code = Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->getTenantKey()],
            '--path' => [$file],
            '--realpath' => true,
            '--force' => true,
        ]);

        if ($code !== 0) {
            throw new RuntimeException(trim(Artisan::output()) ?: 'تعذر إنشاء الجداول');
        }
    }

    protected function markMigrationRan(Tenant $tenant, string $name): void
    {
        $tenant->run(function () use ($name) {
            if (DB::table('migrations')->where('migration', $name)->exists()) {
                return;
            }

            DB::table('migrations')->insert([
                'migration' => $name,
                'batch' => ((int) DB::table('migrations')->max('batch')) + 1,
            ]);
        });
    }

    protected function isAlreadyApplied(Throwable $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'already exists')
            || str_contains($message, 'Duplicate column')
            || str_contains($message, 'Duplicate key name');
    }

    protected function done(Tenant $tenant): array
    {
        return array_values(array_filter((array) $tenant->provision_done));
    }

    protected function nextKey(array $done): ?string
    {
        foreach (array_keys($this->steps()) as $key) {
            if (! in_array($key, $done, true)) {
                return $key;
            }
        }

        return null;
    }

    protected function migrationProgress(Tenant $tenant, array $done): array
    {
        $total = count($this->migrationFiles());

        if (! in_array('database', $done, true)) {
            return ['completed' => 0, 'total' => $total];
        }

        if (in_array('migrate', $done, true)) {
            return ['completed' => $total, 'total' => $total];
        }

        return [
            'completed' => $total - count($this->pendingMigrationFiles($tenant)),
            'total' => $total,
        ];
    }

    protected function migrationFiles(): array
    {
        $files = [];

        foreach ((array) config('tenancy.migration_parameters.--path') as $path) {
            if (! is_dir($path)) {
                continue;
            }

            foreach (File::files($path) as $file) {
                if ($file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }

        usort($files, fn (string $left, string $right) => strcmp(basename($left), basename($right)));

        return $files;
    }

    protected function pendingMigrationFiles(Tenant $tenant): array
    {
        $files = $this->migrationFiles();

        if (! $this->databaseExists($tenant)) {
            return $files;
        }

        try {
            $ran = $tenant->run(fn () => DB::table('migrations')->pluck('migration')->all());
        } catch (Throwable) {
            return $files;
        }

        $ran = array_flip($ran);

        return array_values(array_filter(
            $files,
            fn (string $file) => ! isset($ran[pathinfo($file, PATHINFO_FILENAME)])
        ));
    }

    protected function databaseExists(Tenant $tenant): bool
    {
        try {
            return $tenant->database()->manager()->databaseExists($tenant->databaseName());
        } catch (Throwable) {
            return false;
        }
    }
}

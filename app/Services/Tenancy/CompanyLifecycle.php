<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompanyLifecycle
{
    public function suspend(Tenant $tenant): void
    {
        $tenant->suspended_at = now()->toDateTimeString();
        $tenant->save();
    }

    public function resume(Tenant $tenant): void
    {
        $tenant->suspended_at = null;
        $tenant->save();
    }

    public function delete(Tenant $tenant): void
    {
        $cpanel = app(CpanelClient::class);
        $domains = $tenant->domains()->pluck('domain');

        if ($cpanel->enabled()) {
            foreach ($domains as $domain) {
                $cpanel->deleteSubdomain((string) $domain);
            }

            $cpanel->deleteDatabase($tenant->databaseName());
        } else {
            $this->deleteDatabaseLocally($tenant);
        }

        $storage = storage_path(config('tenancy.filesystem.suffix_base').$tenant->getTenantKey());

        if (is_dir($storage)) {
            File::deleteDirectory($storage);
        }

        $tenant->domains()->delete();
        Tenant::withoutEvents(fn () => $tenant->delete());
    }

    protected function deleteDatabaseLocally(Tenant $tenant): void
    {
        try {
            $name = $tenant->databaseName();

            if ($tenant->database()->manager()->databaseExists($name)) {
                $tenant->database()->manager()->deleteDatabase($tenant);
            }
        } catch (Throwable $exception) {
            Log::warning('Failed to drop tenant database', [
                'tenant' => $tenant->id,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}

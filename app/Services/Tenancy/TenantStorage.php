<?php

namespace App\Services\Tenancy;

use Illuminate\Support\Facades\File;

class TenantStorage
{
    public static function directoryFor(string $tenantId): string
    {
        return base_path('storage/'.config('tenancy.filesystem.suffix_base').$tenantId);
    }

    public static function ensureFrameworkDirectories(?string $root = null): void
    {
        $root = $root ?: storage_path();

        foreach ([
            $root.'/app/public/media',
            $root.'/framework/cache/data',
            $root.'/framework/sessions',
            $root.'/framework/views',
        ] as $directory) {
            File::ensureDirectoryExists($directory);
        }
    }

    /**
     * The public/storage link serves one folder for every host.
     * Company files are served by the tenant /storage route instead.
     */
    public static function detachSharedSymlink(): void
    {
        $link = public_path('storage');

        if (is_link($link)) {
            @unlink($link);

            return;
        }

        if (! is_dir($link)) {
            return;
        }

        $backup = base_path('storage/public-storage-backup');

        if (File::isDirectory($backup)) {
            return;
        }

        File::moveDirectory($link, $backup);
    }
}

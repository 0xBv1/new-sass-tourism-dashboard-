<?php

namespace App\Listeners;

use App\Services\Tenancy\TenantUrl;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ConfigureTenantUrl
{
    public function handle(): void
    {
        $request = request();
        $root = ($request && ! app()->runningInConsole() && $request->getHost())
            ? $request->getSchemeAndHttpHost()
            : TenantUrl::rootForTenant(tenant());

        config(['app.url' => $root]);
        URL::forceRootUrl($root);

        config([
            'filesystems.disks.public.url' => $root.'/storage',
            'filesystems.disks.media.url' => $root.'/storage/media',
        ]);

        Storage::forgetDisk(['public', 'media']);
    }
}

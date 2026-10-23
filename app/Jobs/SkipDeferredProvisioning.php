<?php

namespace App\Jobs;

use App\Models\Tenant;

class SkipDeferredProvisioning
{
    public function __construct(protected Tenant $tenant)
    {
    }

    public function handle(): ?bool
    {
        if ($this->tenant->defer_provisioning) {
            return false;
        }

        return null;
    }
}

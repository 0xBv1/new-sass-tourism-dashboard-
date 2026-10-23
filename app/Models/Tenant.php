<?php

namespace App\Models;

use Carbon\Carbon;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_name',
            'created_at',
            'updated_at',
        ];
    }

    public function databaseName(): string
    {
        return (string) $this->database()->getName();
    }

    public function isSuspended(): bool
    {
        return filled($this->suspended_at);
    }

    public function opensAt(): ?Carbon
    {
        if (blank($this->available_at)) {
            return null;
        }

        return Carbon::parse($this->available_at);
    }

    public function isOpeningSoon(): bool
    {
        return (bool) $this->opensAt()?->isFuture();
    }
}

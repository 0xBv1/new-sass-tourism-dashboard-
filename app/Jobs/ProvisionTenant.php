<?php

namespace App\Jobs;

use App\Enums\SettingKey;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Tenancy\TenantStorage;
use Database\Seeders\TenantDatabaseSeeder;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Spatie\Permission\PermissionRegistrar;

class ProvisionTenant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Tenant $tenant)
    {
    }

    public function handle(): void
    {
        $this->tenant->refresh();

        $this->tenant->run(function () {
            $previousGuard = config('auth.defaults.guard');
            config(['auth.defaults.guard' => 'web']);

            try {
                $this->movePlatformGuardsToWeb();
                (new TenantDatabaseSeeder())->run();

                app(PermissionRegistrar::class)->forgetCachedPermissions();

                $admin = User::updateOrCreate(
                    ['email' => $this->tenant->admin_email],
                    [
                        'name' => $this->tenant->admin_name,
                        'email' => $this->tenant->admin_email,
                        'password' => $this->tenant->admin_password,
                    ]
                );
                $admin->syncRoles(['Administrator']);
            } finally {
                config(['auth.defaults.guard' => $previousGuard]);
            }

            Setting::updateOrCreate(
                ['option_key' => SettingKey::SITE_TITLE->value],
                ['option_value' => [$this->tenant->company_name]]
            );
            Setting::updateOrCreate(
                ['option_key' => SettingKey::EMAIL_ADDRESS->value],
                ['option_value' => [$this->tenant->admin_email]]
            );
            Setting::updateOrCreate(
                ['option_key' => SettingKey::NOTIFICATION_EMAILS->value],
                ['option_value' => array_filter([$this->tenant->admin_email])]
            );

            $hasPersonalClient = Client::query()->get()->contains(function (Client $client) {
                return $client->provider === 'clients'
                    && in_array('personal_access', (array) $client->grant_types, true);
            });

            if (! $hasPersonalClient) {
                app(ClientRepository::class)->createPersonalAccessGrantClient(
                    ($this->tenant->company_name ?: 'Company').' API',
                    'clients'
                );
            }

            TenantStorage::ensureFrameworkDirectories();
        });

        $this->tenant->admin_password = null;
        $this->tenant->save();
    }

    protected function movePlatformGuardsToWeb(): void
    {
        foreach (['roles', 'permissions'] as $table) {
            $webNames = DB::table($table)->where('guard_name', 'web')->pluck('name');

            DB::table($table)
                ->where('guard_name', 'platform')
                ->whereNotIn('name', $webNames)
                ->update(['guard_name' => 'web']);

            DB::table($table)
                ->where('guard_name', 'platform')
                ->whereIn('name', $webNames)
                ->delete();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(PagesSeeder::class);

        Setting::firstOrCreate(
            ['option_key' => SettingKey::SOCIAL_LINKS->value],
            ['option_key' => SettingKey::SOCIAL_LINKS->value, 'option_value' => []]
        );
        Setting::firstOrCreate(
            ['option_key' => SettingKey::COMPANY_TEAM->value],
            ['option_key' => SettingKey::COMPANY_TEAM->value, 'option_value' => []]
        );
    }
}

<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RedirectRulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['redirect-rules.list','redirect-rules.export','redirect-rules.import', 'redirect-rules.create', 'redirect-rules.edit', 'redirect-rules.delete', 'redirect-rules.restore'];
        $permissions_db = [];
        foreach ($permissions as $permission) {
            $permissions_db[] = Permission::updateOrCreate([
                'name' => $permission
            ])->id;
        }

        if ($adminRole = Role::whereName('Administrator')->first()) {
            $adminRole->givePermissionTo($permissions_db);
        }
    }
}

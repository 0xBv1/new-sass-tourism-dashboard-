<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class GroupSizePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['group-sizes.list', 'group-sizes.create', 'group-sizes.edit', 'group-sizes.delete', 'group-sizes.restore'];
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

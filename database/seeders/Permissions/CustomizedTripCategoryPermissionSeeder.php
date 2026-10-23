<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CustomizedTripCategoryPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['customized-trip-categories.list', 'customized-trip-categories.create', 'customized-trip-categories.edit', 'customized-trip-categories.delete', 'customized-trip-categories.restore'];
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

<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CarRoutePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['car-routes.list', 'car-routes.create', 'car-routes.edit', 'car-routes.delete', 'car-routes.restore', 'car-routes.import'];
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

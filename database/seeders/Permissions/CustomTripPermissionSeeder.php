<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CustomTripPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['custom-trips.list', 'custom-trips.show', 'custom-trips.assign', 'custom-trips.un-assign'];
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

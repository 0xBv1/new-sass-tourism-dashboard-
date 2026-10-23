<?php

namespace Database\Seeders;

use Database\Seeders\Permissions\CustomTripPermissionSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $rolesWithPermissions = [
            'Administrator' => ['users', 'roles'],
            'Editor' => [],
            'Operator' => ['custom-trips.list', 'custom-trips.show', 'custom-trips.assign'],
        ];

        foreach ($rolesWithPermissions as $role => $permissions) {
            $dbRole = Role::updateOrCreate([
                'name' => $role
            ]);


            if ($role == 'Administrator') {
                array_map(fn($perm) => Permission::updateOrCreate(['name' => "$perm.list"]), $permissions);
                array_map(fn($perm) => Permission::updateOrCreate(['name' => "$perm.create"]), $permissions);
                array_map(fn($perm) => Permission::updateOrCreate(['name' =>  "$perm.edit"]), $permissions);
                array_map(fn($perm) => Permission::updateOrCreate(['name' =>  "$perm.delete"]), $permissions);
                array_map(fn($perm) => Permission::updateOrCreate(['name' => "$perm.restore"]), $permissions);
                foreach ($permissions as $permission) {
                   $dbRole->givePermissionTo([
                        "$permission.list",
                        "$permission.create",
                        "$permission.edit",
                        "$permission.delete",
                        "$permission.restore",
                    ]);
                }
            }

            if ($role == 'Operator') {
                $this->call(CustomTripPermissionSeeder::class);
                $dbRole->givePermissionTo($permissions);
            }
        }
    }
}

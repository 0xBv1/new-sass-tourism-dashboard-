<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EmployeePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'employees.list',
            'employees.create',
            'employees.edit',
            'employees.delete',
        ];

        $permissions_db = [];
        foreach ($permissions as $permission) {
            $permissions_db[] = Permission::updateOrCreate(['name' => $permission])->id;
        }

        if ($adminRole = Role::whereName('Administrator')->first()) {
            $adminRole->givePermissionTo($permissions_db);
        }
    }
}


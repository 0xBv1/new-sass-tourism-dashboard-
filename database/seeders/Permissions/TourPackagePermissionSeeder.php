<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TourPackagePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'tour-packages.list',
            'tour-packages.create',
            'tour-packages.edit',
            'tour-packages.delete',
        ];

        $permissions_db = [];
        foreach ($permissions as $permission) {
            $permissions_db[] = Permission::firstOrCreate(['name' => $permission])->name;
        }

        // Assign to Administrator role
        if ($adminRole = Role::whereName('Administrator')->first()) {
            $adminRole->givePermissionTo($permissions_db);
        }
    }
}



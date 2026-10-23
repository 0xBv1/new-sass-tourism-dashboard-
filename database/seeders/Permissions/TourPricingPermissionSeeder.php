<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TourPricingPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'tour-pricing.list',
            'tour-pricing.create',
            'tour-pricing.edit',
            'tour-pricing.delete',
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



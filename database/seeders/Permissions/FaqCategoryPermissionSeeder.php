<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class FaqCategoryPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'faq-categories.list',
            'faq-categories.create',
            'faq-categories.edit',
            'faq-categories.delete',
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


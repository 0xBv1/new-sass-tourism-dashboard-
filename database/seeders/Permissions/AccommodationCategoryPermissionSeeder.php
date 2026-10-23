<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AccommodationCategoryPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['accommodation-categories.list', 'accommodation-categories.create', 'accommodation-categories.edit', 'accommodation-categories.delete', 'accommodation-categories.restore'];
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

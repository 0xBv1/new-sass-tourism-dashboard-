<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlogCategoryPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['blog-categories.list', 'blog-categories.create', 'blog-categories.edit', 'blog-categories.delete', 'blog-categories.restore'];
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

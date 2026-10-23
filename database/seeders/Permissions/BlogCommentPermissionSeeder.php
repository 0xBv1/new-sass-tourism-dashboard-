<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlogCommentPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'blog-comments.list',
            'blog-comments.edit',
            'blog-comments.delete',
            'blog-comments.approve',
            'blog-comments.reject',
            'blog-comments.toggle-featured',
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

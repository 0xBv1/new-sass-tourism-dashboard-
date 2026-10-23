<?php

namespace Database\Seeders\Permissions;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class AccommodationPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'accommodations.list',
            'accommodations.create',
            'accommodations.edit',
            'accommodations.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}

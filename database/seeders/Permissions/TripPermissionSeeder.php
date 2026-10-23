<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class TripPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'trips.list', 
            'trips.view',
            'trips.create', 
            'trips.edit', 
            'trips.delete', 
            'trips.restore',
            'trips.view-bookings',
            'trips.toggle-status',
            'trip-bookings.list',
            'trip-bookings.view',
            'trip-bookings.edit',
            'trip-bookings.create',
            'trip-bookings.delete',
            'trip-bookings.update-status'
        ];
        
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
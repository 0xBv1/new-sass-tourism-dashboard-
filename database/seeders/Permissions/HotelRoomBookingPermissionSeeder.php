<?php

namespace Database\Seeders\Permissions;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class HotelRoomBookingPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'hotel_room_bookings.list',
            'hotel_room_bookings.view',
            'hotel_room_bookings.create',
            'hotel_room_bookings.edit',
            'hotel_room_bookings.delete',
            'hotel_room_bookings.update-status',
            'hotel_room_bookings.cancel',
            'hotel_room_bookings.export',
            'hotel_room_bookings.invoice'
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

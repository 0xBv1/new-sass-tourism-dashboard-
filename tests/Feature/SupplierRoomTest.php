<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SupplierRoom;
use App\Models\SupplierHotel;
use App\Models\Supplier;
use App\Models\User;

class SupplierRoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_room_can_be_created()
    {
        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create(['supplier_id' => $supplier->id]);
        
        $roomData = [
            'slug' => 'test-room',
            'supplier_hotel_id' => $hotel->id,
            'enabled' => true,
            'bed_count' => 2,
            'room_type' => 'Standard Room',
            'max_capacity' => 4,
            'bed_types' => '2 Twin Beds',
            'night_price' => 150.00,
            'extra_bed_available' => false,
            'approved' => false,
        ];

        $room = SupplierRoom::create($roomData);
        
        // Add translations
        $room->translateOrNew('en')->fill([
            'name' => 'Test Room',
            'description' => 'A test room for testing purposes.',
        ]);
        $room->save();

        $this->assertDatabaseHas('supplier_rooms', [
            'id' => $room->id,
            'slug' => 'test-room',
            'supplier_hotel_id' => $hotel->id,
        ]);

        $this->assertEquals('Test Room', $room->name);
        $this->assertEquals('A test room for testing purposes.', $room->description);
    }

    public function test_supplier_room_belongs_to_hotel()
    {
        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create(['supplier_id' => $supplier->id]);
        $room = SupplierRoom::factory()->create(['supplier_hotel_id' => $hotel->id]);

        $this->assertInstanceOf(SupplierHotel::class, $room->supplierHotel);
        $this->assertEquals($hotel->id, $room->supplierHotel->id);
    }

    public function test_supplier_room_status_attributes()
    {
        $room = SupplierRoom::factory()->create([
            'approved' => false,
            'enabled' => true,
        ]);

        $this->assertEquals('Pending Approval', $room->status_label);
        $this->assertEquals('warning', $room->status_color);

        $room->update(['approved' => true]);
        $this->assertEquals('Active', $room->status_label);
        $this->assertEquals('success', $room->status_color);

        $room->update(['enabled' => false]);
        $this->assertEquals('Inactive', $room->status_label);
        $this->assertEquals('secondary', $room->status_color);
    }
}

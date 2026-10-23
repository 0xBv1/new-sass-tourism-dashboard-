<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\SupplierHotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SupplierServicesPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        Permission::create(['name' => 'supplier-services.list']);
        Permission::create(['name' => 'supplier-services.edit']);
        Permission::create(['name' => 'supplier-services.approve']);
        Permission::create(['name' => 'supplier-services.reject']);
        
        // Create admin role
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'supplier-services.list',
            'supplier-services.edit',
            'supplier-services.approve',
            'supplier-services.reject'
        ]);
        
        // Create limited role
        $limitedRole = Role::create(['name' => 'Limited Admin']);
        $limitedRole->givePermissionTo(['supplier-services.list']);
    }

    /** @test */
    public function user_with_list_permission_can_access_index()
    {
        $user = User::factory()->create();
        $user->assignRole('Limited Admin');

        $response = $this->actingAs($user)
            ->get(route('dashboard.supplier-services.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_without_list_permission_cannot_access_index()
    {
        $user = User::factory()->create();
        // No role assigned

        $response = $this->actingAs($user)
            ->get(route('dashboard.supplier-services.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_edit_permission_can_access_edit_page()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create(['supplier_id' => $supplier->id]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.supplier-services.edit', ['hotel', $hotel->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_without_edit_permission_cannot_access_edit_page()
    {
        $user = User::factory()->create();
        $user->assignRole('Limited Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create(['supplier_id' => $supplier->id]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.supplier-services.edit', ['hotel', $hotel->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_approve_permission_can_approve_service()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create([
            'supplier_id' => $supplier->id,
            'approved' => false
        ]);

        $response = $this->actingAs($user)
            ->put(route('dashboard.supplier-services.update', ['hotel', $hotel->id]), [
                'approval_status' => 'approved',
                'enabled' => true
            ]);

        $response->assertStatus(302); // Redirect after success
        $this->assertTrue($hotel->fresh()->approved);
    }

    /** @test */
    public function user_without_approve_permission_cannot_approve_service()
    {
        $user = User::factory()->create();
        $user->assignRole('Limited Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create([
            'supplier_id' => $supplier->id,
            'approved' => false
        ]);

        $response = $this->actingAs($user)
            ->put(route('dashboard.supplier-services.update', ['hotel', $hotel->id]), [
                'approval_status' => 'approved',
                'enabled' => true
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_with_reject_permission_can_reject_service()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create([
            'supplier_id' => $supplier->id,
            'approved' => false
        ]);

        $response = $this->actingAs($user)
            ->put(route('dashboard.supplier-services.update', ['hotel', $hotel->id]), [
                'approval_status' => 'rejected',
                'enabled' => false,
                'rejection_reason' => 'Test rejection reason'
            ]);

        $response->assertStatus(302); // Redirect after success
        $this->assertFalse($hotel->fresh()->approved);
        $this->assertEquals('Test rejection reason', $hotel->fresh()->rejection_reason);
    }

    /** @test */
    public function user_without_reject_permission_cannot_reject_service()
    {
        $user = User::factory()->create();
        $user->assignRole('Limited Admin');

        $supplier = Supplier::factory()->create();
        $hotel = SupplierHotel::factory()->create([
            'supplier_id' => $supplier->id,
            'approved' => false
        ]);

        $response = $this->actingAs($user)
            ->put(route('dashboard.supplier-services.update', ['hotel', $hotel->id]), [
                'approval_status' => 'rejected',
                'enabled' => false,
                'rejection_reason' => 'Test rejection reason'
            ]);

        $response->assertStatus(403);
    }
}

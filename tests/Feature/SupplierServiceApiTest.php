<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\SupplierHotel;
use App\Models\SupplierTour;
use App\Models\SupplierTrip;
use App\Models\SupplierTransport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierServiceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a supplier user
        $supplierUser = User::factory()->create();
        $supplierUser->assignRole('Supplier');
        
        // Create a supplier
        $this->supplier = Supplier::factory()->create([
            'user_id' => $supplierUser->id,
            'company_name' => 'Test Travel Agency',
            'commission_rate' => 15.00,
            'is_verified' => true,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_get_all_supplier_services()
    {
        // Create test services
        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        SupplierTour::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/supplier-services');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'supplier_id',
                                'service_type',
                                'service_name',
                                'supplier' => [
                                    'id',
                                    'company_name',
                                    'commission_rate',
                                    'is_verified',
                                    'is_active',
                                ],
                                'approval_status' => [
                                    'approved',
                                    'enabled',
                                    'status_label',
                                    'status_color',
                                ],
                                'commission' => [
                                    'rate',
                                    'rate_formatted',
                                    'amount',
                                    'amount_formatted',
                                ],
                                'recommendation_score',
                            ]
                        ],
                        'pagination' => [
                            'current_page',
                            'per_page',
                            'total',
                            'last_page',
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_recommended_services()
    {
        // Create services with different commission rates
        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/supplier-services/recommended');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'service_type',
                                'service_name',
                                'recommendation_score',
                                'supplier' => [
                                    'company_name',
                                    'commission_rate',
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_services_by_supplier()
    {
        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson("/api/supplier-services/supplier/{$this->supplier->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'supplier_id',
                                'service_type',
                                'service_name',
                            ]
                        ],
                        'pagination',
                    ],
                    'supplier_id'
                ]);
    }

    /** @test */
    public function it_can_get_specific_service()
    {
        $hotel = SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson("/api/supplier-services/hotel/{$hotel->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'supplier_id',
                        'service_type',
                        'service_name',
                        'supplier',
                        'approval_status',
                        'commission',
                        'recommendation_score',
                        'location',
                        'contact',
                        'media',
                        'seo',
                        'details',
                    ]
                ]);
    }

    /** @test */
    public function it_filters_services_by_type()
    {
        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        SupplierTour::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/supplier-services?type=Hotel');

        $response->assertStatus(200);
        
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Hotel', $data[0]['service_type']);
    }

    /** @test */
    public function it_filters_services_by_status()
    {
        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => false,
            'enabled' => true,
        ]);

        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id,
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/supplier-services?status=approved');

        $response->assertStatus(200);
        
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertTrue($data[0]['approval_status']['approved']);
    }

    /** @test */
    public function it_sorts_services_by_commission_rate()
    {
        // Create supplier with higher commission rate
        $highCommissionSupplier = Supplier::factory()->create([
            'commission_rate' => 25.00,
            'is_verified' => true,
            'is_active' => true,
        ]);

        SupplierHotel::factory()->create([
            'supplier_id' => $this->supplier->id, // 15% commission
            'approved' => true,
            'enabled' => true,
        ]);

        SupplierHotel::factory()->create([
            'supplier_id' => $highCommissionSupplier->id, // 25% commission
            'approved' => true,
            'enabled' => true,
        ]);

        $response = $this->getJson('/api/supplier-services?sort_by=commission_rate&sort_order=desc');

        $response->assertStatus(200);
        
        $data = $response->json('data.data');
        $this->assertCount(2, $data);
        // First service should have higher commission rate
        $this->assertEquals(25.00, $data[0]['supplier']['commission_rate']);
        $this->assertEquals(15.00, $data[1]['supplier']['commission_rate']);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_service()
    {
        $response = $this->getJson('/api/supplier-services/hotel/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
    }
}

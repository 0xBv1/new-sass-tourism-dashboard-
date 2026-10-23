<?php

namespace Tests\Feature;

use Tests\TestCase;

class SupplierServiceApiBasicTest extends TestCase
{
    /** @test */
    public function it_can_access_supplier_services_endpoint()
    {
        $response = $this->getJson('/api/supplier-services');

        // Should return 200 even if no data (empty collection)
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data',
                        'pagination' => [
                            'current_page',
                            'per_page',
                            'total',
                            'last_page',
                            'from',
                            'to',
                        ],
                        'filters',
                    ],
                ]);
    }

    /** @test */
    public function it_can_access_recommended_services_endpoint()
    {
        $response = $this->getJson('/api/supplier-services/recommended');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data',
                        'filters',
                    ],
                ]);
    }

    /** @test */
    public function it_can_access_supplier_services_by_supplier_endpoint()
    {
        $response = $this->getJson('/api/supplier-services/supplier/1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data',
                        'pagination',
                    ],
                    'supplier_id',
                ]);
    }

    /** @test */
    public function it_can_access_specific_service_endpoint()
    {
        $response = $this->getJson('/api/supplier-services/hotel/1');

        // Should return 404 for non-existent service, but endpoint should be accessible
        $response->assertStatus(404)
                ->assertJsonStructure([
                    'success',
                    'message',
                ]);
    }

    /** @test */
    public function it_accepts_query_parameters()
    {
        $response = $this->getJson('/api/supplier-services?type=Hotel&city=Cairo&per_page=10');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data',
                        'pagination',
                        'filters',
                    ],
                ]);
    }

    /** @test */
    public function it_accepts_recommended_parameters()
    {
        $response = $this->getJson('/api/supplier-services/recommended?type=Tour&limit=5');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data',
                        'filters',
                    ],
                ]);
    }
}

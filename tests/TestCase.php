<?php

namespace Tests;

use App\Models\Client;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function login(): void
    {
        $user = Client::factory()->create();
        $this->actingAs($user, 'client');
    }
}

<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\AuthorizationTestCase;


class ModulesControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get('/api/v6/modules');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testIndexWithApiKey()
    {
        Sanctum::actingAs(
            $this->adminUser,
            ['*']
        );
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get('/api/v6/modules');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }
}

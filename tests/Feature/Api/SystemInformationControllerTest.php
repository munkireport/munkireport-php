<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class SystemInformationControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testDatabase()
    {
        $response = $this->actingAs($this->user)
            ->get('/api/v6/system/database');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testPhp()
    {
        $response = $this->actingAs($this->user)
            ->get('/api/v6/system/php');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }
}

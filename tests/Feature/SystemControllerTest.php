<?php

namespace Tests\Feature;

use App\Http\Controllers\SystemController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class SystemControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testGetDataBaseInfo()
    {
        $response = $this->actingAs($this->user)
            ->get('/system/DataBaseInfo');

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertOk()
            ->assertJsonStructure([
                'db.driver',
                'db.connectable',
                'db.writable',
                'db.size',
                'error',
                'version']);
    }

    public function testGetPhpInfo()
    {
        $response = $this->actingAs($this->user)
            ->get('/system/phpInfo');

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertOk();
        $this->assertNotNull($response->jsonGet('phpinfo')); // Too many keys to test really.
    }

    public function testShowAsUser()
    {
        $response = $this->actingAs($this->user)
            ->get('/system/status');
        $response->assertStatus(403);
    }

    public function testShowAsAdmin()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/system/status');
        $response->assertOk();
    }

    public function testRedirectShowActions()
    {
        $this->markTestIncomplete('This test should make sure that anyone arriving at /system/show/<x> should be redirected');
    }

//    public function testShowNotFoundAsUser()
//    {
//        $response = $this->actingAs($this->user)
//            ->get('/system/show/xyzabc');
//        $response->assertStatus(403);
//    }
//
//    public function testShowNotFound()
//    {
//        $response = $this->actingAs($this->adminUser)
//            ->get('/system/show/xyzabc');
//        $response->assertNotFound();
//    }
}

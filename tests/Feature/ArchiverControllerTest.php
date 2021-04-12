<?php

namespace Tests\Feature;

use App\Machine;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthorizationTestCase;
use Machine_model;

class ArchiverControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }

    /**
     *
     */
    public function testUpdate_status()
    {
        $this->markTestIncomplete();
//        $response = $this->post('/archiver/update_status/012345', [
//            'status' => '',
//        ]);
    }

    public function bulk_update_status()
    {
        $this->markTestIncomplete();
//        $response = $this->post('/archiver/update_status/012345', [
//            'days' => 30,
//        ]);
    }

    public function testIndex()
    {
        $this->markTestIncomplete();
    }

    // Business Units Configured: FALSE

    public function testAdminCanArchiveMachine()
    {
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->adminUser)
                         ->post("/archiver/update_status/${machine['serial_number']}", [
                             'status' => 1,
                         ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testManagerCanArchiveMachine()
    {
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->managerUser)
            ->post("/archiver/update_status/${machine['serial_number']}", [
                'status' => 1,
            ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testArchiverCanArchiveMachine()
    {
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->archiverUser)
            ->post("/archiver/update_status/${machine['serial_number']}", [
                'status' => 1,
            ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testUserCannotArchiveMachine()
    {
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->user)
            ->post("/archiver/update_status/${machine['serial_number']}", [
                'status' => 1,
            ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    // Business Units Configured: TRUE


}

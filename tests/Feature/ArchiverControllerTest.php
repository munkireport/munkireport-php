<?php

namespace Tests\Feature;

use App\Machine;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
        $reportData = factory(\App\ReportData::class)->create();
        $response = $this->actingAs($this->adminUser)->post(
            '/archiver/update_status/' . $reportData->serial_number,
            ['status' => '1']);
        $response->assertOk();

    }

    public function test_bulk_update_status()
    {
        $daysTooOld = new \DateInterval("P4D");
        $reportData = factory(\App\ReportData::class)->create([
            'timestamp' => Carbon::now()->subtract($daysTooOld)->unix(),
            'archive_status' => 0,
        ]);
        $response = $this->actingAs($this->adminUser)
                         ->post('/archiver/bulk_update_status', ['days' => 1]);
        $response->assertOk();
        $this->assertEquals(1, $reportData->archive_status);
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

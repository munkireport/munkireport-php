<?php

namespace Tests\Feature;

use App\Machine;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
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
        $reportData = \App\ReportData::factory()->create();
        $response = $this->actingAs($this->adminUser)->post(
            '/archiver/update_status/' . $reportData->serial_number,
            ['status' => '1']);
        $response->assertOk();
        $response->assertJson(['updated' => 1]);
    }

    public function test_bulk_update_status()
    {
        $daysTooOld = new \DateInterval("P4D");
        $reportData = \App\ReportData::factory()->create([
            'timestamp' => Carbon::now()->subtract($daysTooOld)->unix(),
            'archive_status' => 0,
        ]);
        $response = $this->actingAs($this->adminUser)
                         ->post('/archiver/bulk_update_status', ['days' => 1]);
        $response->assertOk();
        $this->assertEquals(1, $reportData->archive_status);
    }

    // Business Units Configured: FALSE
    // https://github.com/munkireport/munkireport-php/wiki/Authorization%2C-Roles-and-Groups#no-business-units

    public function testAdminCanArchiveMachine()
    {
        $reportData = \App\ReportData::factory()->create([
            'archive_status' => 0,
        ]);
        $machine = Machine::factory()->create(['serial_number' => $reportData->serial_number]);
        $response = $this->actingAs($this->adminUser)
                         ->post("/archiver/update_status/${machine['serial_number']}", [
                             'status' => 1,
                         ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testManagerCanArchiveMachine()
    {
        $reportData = \App\ReportData::factory()->create([
            'archive_status' => 0,
        ]);
        $machine = Machine::factory()->create(['serial_number' => $reportData->serial_number]);
        $response = $this->actingAs($this->managerUser)
                        ->post("/archiver/update_status/${machine['serial_number']}", [
                            'status' => 1,
                        ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testArchiverCanArchiveMachine()
    {
        $reportData = \App\ReportData::factory()->create([
            'archive_status' => 0,
        ]);
        $machine = Machine::factory()->create(['serial_number' => $reportData->serial_number]);
        $response = $this->actingAs($this->archiverUser)
            ->post("/archiver/update_status/${machine['serial_number']}", [
                'status' => 1,
            ]);
        $response->assertStatus(200);
        $response->assertJson(['updated' => 1]);
    }

    public function testUserCannotArchiveMachine()
    {
        $reportData = \App\ReportData::factory()->create([
            'archive_status' => 0,
        ]);
        $machine = Machine::factory()->create(['serial_number' => $reportData->serial_number]);

        $this->expectException(AuthorizationException::class);
        $response = $this->actingAs($this->user)
            ->post("/archiver/update_status/${machine['serial_number']}", [
                'status' => 1,
            ]);
        $response->assertStatus(403);
    }

    // Business Units Configured: TRUE
    // https://github.com/munkireport/munkireport-php/wiki/Authorization%2C-Roles-and-Groups#business-units
}

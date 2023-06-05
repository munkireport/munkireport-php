<?php

namespace Tests\Feature;

use App\Http\Controllers\ManagerController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthorizationTestCase;

class ManagerControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->reportData = \App\ReportData::factory()->create([
            'machine_group' => $this->legacyMachineGroup['name']['groupid'],
            'reg_timestamp' => time(),
        ]);

        $this->machine = \App\Machine::factory()->create([
            'serial_number' => $this->reportData->serial_number,
        ]);

        $this->foreignReportData = \App\ReportData::factory()->create([
            'machine_group' => $this->legacyForeignMachineGroup['name']['groupid'],
            'reg_timestamp' => time(),
        ]);

        $this->foreignMachine = \App\Machine::factory()->create([
            'serial_number' => $this->foreignReportData->serial_number,
        ]);
    }

    /// VALIDATION OF MACHINE POLICY for v5
    /// https://github.com/munkireport/munkireport-php/wiki/Authorization%2C-Roles-and-Groups

    /**
     * Without Business Units Enabled: Managers may delete machines
     */
    public function testWithoutBuManagersCanDelete()
    {
        $response = $this->actingAs($this->managerUser)
            ->get("/manager/delete_machine/{$this->machine->serial_number}");
        $response->assertOk();
    }

    /**
     * Without Business Units Enabled: Archivers cannot delete machines
     */
    public function testWithoutBuArchiversCannotDelete()
    {
        $response = $this->actingAs($this->archiverUser)
            ->get("/manager/delete_machine/{$this->machine->serial_number}");
        $response->assertStatus(403);
    }

    /**
     * With Business Units Enabled: Managers cannot delete machines business units they are not a member of
     */
    public function testWithBuManagersOfOtherBuCannotDelete() {

        $response = $this->actingAs($this->managerUser)
            ->get("/manager/delete_machine/{$this->foreignMachine->serial_number}");
        $response->assertStatus(403);
    }

    /**
     * Admins may always delete regardless of authz
     */
    public function testAdminsAlwaysDelete() {
        $response = $this->actingAs($this->adminUser)
            ->get("/manager/delete_machine/{$this->machine->serial_number}");
        $response->assertOk();
    }

    /**
     * Users can never delete
     */
    public function testWithBuUsersCannotDelete() {
        $response = $this->actingAs($this->user)
            ->get("/manager/delete_machine/{$this->foreignMachine->serial_number}");
        $response->assertStatus(403);
    }
}

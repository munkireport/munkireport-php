<?php

namespace Tests\Feature;

use App\Machine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class MachineControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    private $reportData;
    private $machine;

    public function setUp(): void
    {
        parent::setUp();

        $this->reportData = factory(\App\ReportData::class)->create([
            'reg_timestamp' => time(),
        ]);
        $this->machine = factory(\App\Machine::class)->create([
            'serial_number' => $this->reportData->serial_number,
        ]);
    }

    public function test_get_duplicate_computernames()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_duplicate_computernames");

        $response->assertOk();

    }

    public function test_get_model_stats()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_model_stats");

        $response->assertOk();
    }

    public function test_get_model_stats_summary()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_model_stats/summary");

        $response->assertOk();
    }

    public function testReport()
    {
        $reportData = factory(\App\ReportData::class)->create();
        $machine = factory(\App\Machine::class)->create([
            'serial_number' => $reportData->serial_number,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/module/machine/report/{$machine->serial_number}");

        $response->assertOk();
        $response->assertJson([
            'id',
            'serial_number',
            'hostname',
            'machine_model',
            'machine_desc',
            'img_url',
            'cpu',
            'current_processor_speed',
            'cpu_arch',
            'os_version',
            'physical_memory',
            'platform_UUID',
            'number_processors',
            'SMC_version_system',
            'boot_rom_version',
            'bus_speed',
            'computer_name',
            'l2_cache',
            'machine_name',
            'packages',
            'buildversion',
            'console_user',
            'long_username',
            'remote_ip',
            'uptime',
            'machine_group',
            'reg_timestamp',
            'timestamp',
            'uid',
            'archive_status',
        ]);
    }

    public function test_new_clients()
    {
        $reportData = factory(\App\ReportData::class)->create([
            'reg_timestamp' => time(),
        ]);
        $machine = factory(\App\Machine::class)->create([
            'serial_number' => $reportData->serial_number,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/module/machine/new_clients");

        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_get_memory_stats_format_none()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_memory_stats");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'count',
            ],
        ]);
    }

    public function test_get_memory_stats_format_flotr()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_memory_stats/flotr");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'data' => [],
            ],
        ]);
    }

    public function test_get_memory_stats_format_button()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/get_memory_stats/button");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'count',
            ],
        ]);
    }

    public function test_hw()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/hw");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'count',
            ],
        ]);
    }

    public function test_os()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/os");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'count',
            ],
        ]);
    }

    public function test_osbuild()
    {
        $response = $this->actingAs($this->user)
            ->get("/module/machine/osbuild");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'label',
                'count',
            ],
        ]);
    }
}

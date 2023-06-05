<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportDataController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthorizationTestCase;

class ReportDataControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testGet_groups()
    {
        $reportData = \App\ReportData::factory()->create();
        $response = $this->actingAs($this->user)
            ->get("/module/reportdata/get_groups");
        $response->assertOk();
        $response->assertJson([
            '*' => [
                'machine_group',
                'cnt',
            ],
        ]);
    }

    public function testGetUptimeStats()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testIp()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testGet_lastseen_stats()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testNew_clients()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testGet_inactive_days()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testNew_clients2()
    {
        $this->markTestIncomplete("TODO");
    }

    public function testReport()
    {
        $reportData = \App\ReportData::factory()->create();
        $response = $this->actingAs($this->user)
            ->get("/module/reportdata/report/$reportData->serial_number");
        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'serial_number',
            'console_user',
            'long_username',
            'remote_ip',
            'uptime',
            'machine_group',
            'reg_timestamp',
            'timestamp',
            'uid',
            'archive_status'
        ]);
    }
}

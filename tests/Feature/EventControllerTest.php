<?php

namespace Tests\Feature;

use App\Http\Controllers\EventController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthorizationTestCase;

class EventControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testGet()
    {
        $reportData = \App\ReportData::factory()->create();
        $event = \App\Event::factory()->create([
            'serial_number' => $reportData->serial_number,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/module/event/get/50");
        $response->assertOk();
        $response->assertJsonStructure([
            'error',
            'items' => [
                '*' => [
                    'id',
                    'serial_number',
                    'type',
                    'module',
                    'msg',
                    'data',
                    'timestamp',
                ]
            ],
        ]);
    }
}

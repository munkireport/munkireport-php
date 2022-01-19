<?php

namespace Tests\Feature;

use App\Http\Controllers\TagController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\AuthorizationTestCase;

class TagControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testRetrieve()
    {
        $reportData = \App\ReportData::factory()->create();
        // TODO: create tag
        $response = $this->actingAs($this->user)
            ->get("/module/tag/retrieve/{$reportData->serial_number}");
        $response->assertOk();
        $response->assertJsonStructure(['*']);

    }

    public function testDelete()
    {

    }

    public function testSave()
    {
        $reportData = \App\ReportData::factory()->create();
        $data = [
            'serial_number' => $reportData->serial_number,
            'tag' => 'testing',
        ];
        $response = $this->actingAs($this->user)
            ->postJson('/module/tag/save', $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'serial_number',
            'tag',
            'user',
            'timestamp',
            'id',
        ]);
    }

    public function testListing()
    {

    }

    public function testAll_tags()
    {
        $reportData = \App\ReportData::factory()->create();
        $response = $this->actingAs($this->user)
            ->get('/module/tag/all_tags');
        $response->assertOk();
        $response->assertJsonStructure(['*']);
    }
}

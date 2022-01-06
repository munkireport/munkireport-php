<?php

namespace Tests\Feature\Api;

use App\MachineGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class MachineGroupsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $machineGroup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->machineGroup = MachineGroup::factory()->create();
    }

    protected function tearDown(): void
    {
        $this->machineGroup = null;
        parent::tearDown();
    }

    public function testIndex()
    {
        $response = $this->get('/api/v6/machine_groups');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testCreate()
    {
        $response = $this->postJson('/api/v6/machine_groups', [
            'data' => [
                'name' => 'Test Machine Group',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['data' => ['key', 'name', 'id']]);
    }

    public function testUpdate()
    {
        $response = $this->patchJson("/api/v6/machine_groups/{$this->machineGroup->id}", [
            'data' => [
                'name' => 'Updated Name',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['data' => ['key', 'name', 'id']]);
        $this->assertEquals('Updated Name', $response->json('data.name'));
    }

    public function testDelete()
    {
        $id = $this->machineGroup->id;
        $response = $this->deleteJson("/api/v6/machine_groups/{$id}");
        $response->assertStatus(204);
    }
}

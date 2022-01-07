<?php

namespace Tests\Feature\Api;

use App\MachineGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\AuthorizationTestCase;
use Tests\TestCase;


class MachineGroupsControllerTest extends AuthorizationTestCase
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
        $response = $this->actingAs($this->user)->get('/api/v6/machine_groups');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testIndexWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
        $response = $this->get('/api/v6/machine_groups');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v6/machine_groups', [
            'data' => [
                'name' => 'Test Machine Group',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['data' => ['key', 'name', 'id']]);
    }

    public function testCreateWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
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
        $response = $this->actingAs($this->user)->patchJson("/api/v6/machine_groups/{$this->machineGroup->id}", [
            'data' => [
                'name' => 'Updated Name',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['data' => ['key', 'name', 'id']]);
        $this->assertEquals('Updated Name', $response->json('data.name'));
    }

    public function testUpdateWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
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
        $response = $this->actingAs($this->user)->deleteJson("/api/v6/machine_groups/{$id}");
        $response->assertStatus(204);
    }

    public function testDeleteWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
        $id = $this->machineGroup->id;
        $response = $this->actingAs($this->user)->deleteJson("/api/v6/machine_groups/{$id}");
        $response->assertStatus(204);
    }
}

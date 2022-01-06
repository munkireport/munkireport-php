<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/v6/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(["data"]);
        $this->assertGreaterThan(0, count($response->jsonGet("data")),
            "Assert at least one result is present");
        $this->assertEquals($user->name, $response->json("data")[0]["name"]);
    }

    public function testStore()
    {
        $user =  User::factory()->make();
        $response = $this->postJson('/api/v6/users', ["data" => $user->toArray()]);
        $response->assertStatus(201);
        $response->assertJsonStructure(["data"]);
        print_r($response->json());
    }

    public function testUpdate()
    {
        $user = User::factory()->create(["email" => "testupdate@test.abc"]);
        $user->email = "testupdate@test.def";

        $response = $this->putJson("/api/v6/users/{$user->id}", ["data" => $user->toArray()]);
        $response->assertStatus(200);
        $response->assertJsonStructure(["data"]);
        $this->assertEquals("testupdate@test.def", $response->jsonGet("data.email"));
    }

    public function testGet()
    {
        $user = User::factory()->create();
        $response = $this->get("/api/v6/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(["data"]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $response = $this->delete("/api/v6/users/{$user->id}");
        $response->assertStatus(204);
    }

}

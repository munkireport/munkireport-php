<?php

namespace Tests\Feature\Api;

use App\User;
use App\UserContactMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\AuthorizationTestCase;

class UsersContactMethodsTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();
        $user->contactMethods()->save(UserContactMethod::factory()->make());

        $response = $this->actingAs($this->user)->get("/api/v6/users/{$user->id}/contact_methods");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testIndexWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $user = User::factory()->create();
        $user->contactMethods()->save(UserContactMethod::factory()->make());

        $response = $this->get("/api/v6/users/{$user->id}/contact_methods");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $contactMethod = UserContactMethod::factory()->make();
        $response = $this->actingAs($this->user)->postJson("/api/v6/users/{$user->id}/contact_methods",
            ["data" => $contactMethod->toArray()]);
        $response->assertStatus(201);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(["data" => ["user_id", "channel", "address"]]);
    }

    public function testStoreWithApiKey()
    {
        Sanctum::actingAs(
            $this->user,
            ['*']
        );
        $user = User::factory()->create();

        $contactMethod = UserContactMethod::factory()->make();
        $response = $this->postJson("/api/v6/users/{$user->id}/contact_methods",
            ["data" => $contactMethod->toArray()]);

        $response->assertStatus(201);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(["data" => ["user_id", "channel", "address"]]);
    }

    public function testGet()
    {
        $user = User::factory()->create();
        $contactMethod = UserContactMethod::factory()->make();
        $user->contactMethods()->save($contactMethod);

        $response = $this->actingAs($this->user)->get("/api/v6/users/{$user->id}/contact_methods/{$contactMethod->id}");
        print_r($response->json());
        $response->assertStatus(200);
        $response->assertJsonStructure(["data" => ["user_id", "channel", "address"]]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $contactMethod = UserContactMethod::factory()->make();
        $user->contactMethods()->save($contactMethod);

        $contactMethod->address = "updated_address";

        $response = $this->actingAs($this->user)->putJson("/api/v6/users/{$user->id}/contact_methods/{$contactMethod->id}",
            ["data" => $contactMethod->toArray()]);
        print_r($response->json());
        $response->assertStatus(200);
        $response->assertJsonStructure(["data" => ["user_id", "channel", "address"]]);
//        $this->assertEquals("updated_address", $response->("data.address"));
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $contactMethod = UserContactMethod::factory()->make();
        $user->contactMethods()->save($contactMethod);

        $response = $this->actingAs($this->user)->delete("/api/v6/users/{$user->id}/contact_methods/{$contactMethod->id}");
        $response->assertStatus(204);
    }
}

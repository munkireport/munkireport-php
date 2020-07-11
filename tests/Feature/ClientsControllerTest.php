<?php

namespace Tests\Feature;

use App\Http\Controllers\ClientsController;
use App\Machine;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Machine_model;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    protected function tearDown(): void
    {
        $this->user = null;
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testGet_links()
    {
        $response = $this->actingAs($this->user)
            ->get('/clients/get_links');
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertOk()
                 ->assertJsonStructure(['vnc', 'ssh']);
    }

    public function testGet_data()
    {
        $machine = factory(Machine_model::class)->create();
        $response = $this->actingAs($this->user)
            ->get("/clients/get_data/${machine['serial_number']}");
    }

    public function testDetail()
    {
        $response = $this->actingAs($this->user)
            ->get("/clients/detail/nonexistentserial");

        $response->assertSeeText('errors.client_nonexistent');
    }
}
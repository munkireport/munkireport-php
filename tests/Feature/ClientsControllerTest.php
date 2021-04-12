<?php

namespace Tests\Feature;

use App\Http\Controllers\ClientsController;
use App\Machine;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Machine_model;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class ClientsControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    protected $user;
    protected $adminUser;
    protected $managerUser;
    protected $archiverUser;

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
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->user)
            ->get("/clients/get_data/${machine['serial_number']}");
        $response->assertOk();
    }

    public function testDetail()
    {
        $response = $this->actingAs($this->user)
            ->get("/clients/detail/nonexistentserial");

        $response->assertSeeText('errors.client_nonexistent');
    }


    // User cannot see archive button when BU are disabled

    public function testDetailUserCannotSeeArchiveButton()
    {
        $machine = factory(Machine::class)->create();
        $response = $this->actingAs($this->user)
            ->get("/clients/detail/${machine['serial_number']}");

        $response->assertDontSeeText('Archive');
    }
}

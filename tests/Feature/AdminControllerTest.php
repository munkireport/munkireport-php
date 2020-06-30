<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  POST /admin/save_machine_group
     */
    public function testSaveMachineGroup()
    {
        $response = $this->post('/admin/save_machine_group');
        $response->assertStatus(200);
    }

    /**
     *  /admin/remove_machine_group
     */
    public function testRemoveMachineGroup()
    {
        $response = $this->post('/admin/remove_machine_group');
        $response->assertStatus(200);
    }

    /**
     *  POST /admin/save_business_unit
     */
    public function testSaveBusinessUnit()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->post('/admin/save_business_unit', [
                            'unitid' => 'new',
                            'users[]' => '#',
                            'archivers[]' => '#',
                            'managers[]' => '#',
                            'machine_groups[]' => '#',
                            'name' => 'testSaveBusinessUnit',
                            'address' => 'testSaveBusinessUnitAddr',
                            'link' => 'http://test.save.business.unit.example.com',
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('status_code', 200);
    }

    /**
     *  POST /admin/remove_business_unit
     */
    public function testRemoveBusinessUnit()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->post('/admin/save_business_unit', [
                'unitid' => 'new',
                'users[]' => '#',
                'archivers[]' => '#',
                'managers[]' => '#',
                'machine_groups[]' => '#',
                'name' => 'testSaveBusinessUnit',
                'address' => 'testSaveBusinessUnitAddr',
                'link' => 'http://test.save.business.unit.example.com',
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('status_code', 200);
    }

    /**
     *  GET /admin/get_bu_data
     */
    public function testGetBuData()
    {

    }

    /**
     *  GET /admin/get_mg_data
     */
    public function testGetMgData()
    {

    }
}


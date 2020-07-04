<?php

namespace Tests\Feature;

use App\BusinessUnit;
use App\MachineGroup;
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
        $response = $this->post('/admin/save_machine_group', [
            'groupid' => '',
            'name' => 'fixture machine group name',
            'key' => '12B652B7-1028-FB9B-EA8B-27D7E6378794',
        ], ['Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8']);
        $response->assertOk();
        $response->assertJsonMissing(['error' => 'Groupid is missing']);
        $response->assertJsonPath('name', 'fixture machine group name');
        $response->assertJsonStructure([
            'groupid' => 1,
            'name' => 'name',
            'keys' => [
                'key',
            ],
        ]);
    }

    /**
     *  /admin/remove_machine_group
     */
    public function testRemoveMachineGroup()
    {
        $response = $this->get('/admin/remove_machine_group/2');
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
//        $businessUnit = factory(BusinessUnit::class)->create();

        $response = $this->get('/admin/get_bu_data');
        $response->assertOk();
        $this->assertIsArray($response->json());
        $response->assertJsonStructure([
            'users',
            'managers',
            'archivers',
            'machine_groups',
            'name',
            'unitid',
            'address',
            'link',
        ]);

    }

    /**
     *  GET /admin/get_mg_data
     */
    public function testGetMgData()
    {
        // $machineGroup = factory(MachineGroup::class)->create();

        $response = $this->get('/admin/get_mg_data');
        $response->assertOk();
        $this->assertIsArray($response->json());
        $response->assertJsonStructure([
            'name',
            'groupid',
            'cnt',
            'keys',
        ]);

    }
}


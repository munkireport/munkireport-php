<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use munkireport\lib\BusinessUnit;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class AdminControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    /**
     *  POST /admin/save_machine_group
     */
    public function testSaveMachineGroup()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/save_machine_group', [
            'groupid' => '',
            'name' => 'fixture machine group name',
            'key' => '12B652B7-1028-FB9B-EA8B-27D7E6378794',
        ]);
        // $response->dump();
        $response->assertJsonMissing(['error' => 'Groupid is missing']);
        $response->assertJsonPath('groupid', 1);
        $response->assertOk();
        $response->assertJsonStructure([
            'groupid',
            'name',
            'keys' => [],
        ]);
    }

    /**
     *  GET /admin/get_mg_data
     */
    public function testGetMgData()
    {
        // $machineGroup = factory(MachineGroup::class)->create();
        $response = $this->actingAs($this->adminUser)
                         ->get('/admin/get_mg_data');
        $response->assertOk();
        $this->assertIsArray($response->json());
        $response->assertJsonStructure([
            '*' => [
                'name',
                'groupid',
                'keys',
                'cnt',
            ]
        ]);
    }

    /**
     *  /admin/remove_machine_group
     */
    public function testRemoveMachineGroup()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/remove_machine_group', [
                'groupid' => 1
            ]);
        $response->assertStatus(200);
    }

    /**
     *  POST /admin/save_business_unit
     */
    public function testSaveBusinessUnit()
    {
        $response = $this->actingAs($this->adminUser)
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
            ->assertJsonStructure([
                'unitid',
                'name',
                'address',
                'link',
            ])
            ->assertJsonFragment([
                'name' => 'testSaveBusinessUnit',
                'address' => 'testSaveBusinessUnitAddr',
                'link' => 'http://test.save.business.unit.example.com',
            ]);
    }

    /**
     * POST same as above, except the new machine group detail will be:
     * iteminfo[0][key]: ""
     * iteminfo[1][name]: "Machine Group Name"
     */
    public function testSaveBusinessUnitMachineGroup()
    {
        $this->markTestIncomplete();
    }

    /**
     *  POST /admin/remove_business_unit
     */
    public function testRemoveBusinessUnit()
    {
        $bu = new BusinessUnit();
        $result = $bu->saveUnit([
            'unitid' => 'new',
            'name' => 'testDeleteBusinessUnit',
            'address' => 'testDeleteBusinessUnitAddr',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/remove_business_unit', [
                'unitid' => 'new',
                'users[]' => '#',
                'archivers[]' => '#',
                'managers[]' => '#',
                'machine_groups[]' => '#',
                'name' => 'testSaveBusinessUnit',
                'address' => 'testSaveBusinessUnitAddr',
                'link' => 'http://test.save.business.unit.example.com',
            ]);

        $response->assertStatus(200);
    }

    /**
     *  GET /admin/get_bu_data
     */
    public function testGetBuData()
    {
//        $businessUnit = factory(BusinessUnit::class)->create();

        $bu = new BusinessUnit();
        $result = $bu->saveUnit([
            'unitid' => 'new',
            'name' => 'testDeleteBusinessUnit',
            'address' => 'testDeleteBusinessUnitAddr',
            'link' => 'http://something',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/get_bu_data');
        $response->assertOk();
        $this->assertIsArray($response->json());
        $response->assertJsonStructure([
            '*' => [
                'users',
                'managers',
                'archivers',
                'machine_groups',
                'name',
                'unitid',
                'address',
                'link',
            ]
        ]);

        $this->assertEquals('testDeleteBusinessUnit', $response->jsonGet('0.name'));
        $this->assertEquals('testDeleteBusinessUnitAddr', $response->jsonGet('0.address'));
    }
}


<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Compatibility\Service\BusinessUnit;
use Tests\AuthorizationTestCase;
use Tests\TestCase;

class AdminControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    /**
     *  POST /admin/save_machine_group
     *
     *  Using the Business Units interface in MunkiReport 5.6.5 to add a new machine group to a business unit does not
     *  invoke this endpoint. It uses /admin/save_business_unit with iteminfo[] populated using the new machine group(s).
     *
     *  This endpoint is only used for the "Update" part of CRUD.
     *
     *  Example:
     *
     *  Request (application/x-www-form-urlencoded)
     *
     *  POST /admin/save_machine_group
     *
     *  groupid    "2"
     *  name    "New+Machine+Group2"
     *  key    "1CBD77DF-711F-4BC2-B9D6-264EE660C065"
     *  business_unit    "1"
     *
     *  Response (application/json;charset=utf-8)
     *
     *  {"groupid":2,"name":"New Machine Group2","business_unit":1,"keys":["1CBD77DF-711F-4BC2-B9D6-264EE660C065"]}
     */
    public function testSaveMachineGroup()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/save_machine_group', [
                'groupid' => '',
                'name' => 'fixture machine group name',
                'key' => '12B652B7-1028-FB9B-EA8B-27D7E6378794',
                'business_unit' => '1',
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
     *
     *  In MunkiReport 5.6.5 this endpoint is called upon page load of the business units admin interface, along with
     *  get_bu_data etc to populate the page. The controller accepts a single parameter of groupid, but that is never
     *  used by the frontend.
     *
     *  Example:
     *
     *  Request:
     *
     *  GET /index.php?/admin/get_mg_data= (application/json;charset=utf-8)
     *
     *  Response:
     *
     *  [{"name":"Default Group","groupid":1,"keys":["2A53229A-731C-50D3-D303-49864F2DCF50"]},{"name":"New Machine Group2","groupid":2,"keys":["1CBD77DF-711F-4BC2-B9D6-264EE660C065"]},{"groupid":0,"name":"Group 0","cnt":1}]
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
                //'keys',  // if the group is the default one, this key is not present
                //'cnt',  // if the group has zero machines, the `cnt` key is not present
            ]
        ]);
    }

    /**
     *  /admin/remove_machine_group
     *
     *  In MunkiReport 5.6.5 this is invoked by editing a machine group using the unassigned groups edit button in the lower section
     *  of the business units UI, and clicking the delete button inside the edit dialog. This is never called using a POST verb
     *
     *  Request:
     *
     *  GET /admin/remove_machine_group/2
     *
     *  Response (application/json;charset=utf-8):
     *
     *  {"success":true,"successs":0}
     */
    public function testRemoveMachineGroup()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/remove_machine_group/1');
        $response->assertStatus(200);
    }

    /**
     * Create a new Business Unit (v5)
     *
     *  POST /admin/save_business_unit
     */
    public function testSaveBusinessUnit()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/save_business_unit', [
                'name' => 'testSaveBusinessUnit',
                # groupid  # The business unit ID
                # keys[]
                'unitid' => 'new',
                'users[]' => '#',
                'archivers[]' => '#',
                'managers[]' => '#',
                'machine_groups[]' => '#',
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
     * This integration test replicates the XHR calls from jQuery when you save a new machine group into an existing
     * Business Unit i.e
     *
     *  POST /admin/save_business_unit (with form encoded data)
     *  GET /admin/get_mg_data
     *
     * POST same as above, except the new machine group detail will be:
     * iteminfo[0][key]: ""
     * iteminfo[1][name]: "Machine Group Name"
     */
    public function testNewMachineGroupV5()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/save_business_unit', [
                'unitid' => 'new',  # or an existing unit id number
                'users[]' => '#',
                'archivers[]' => '#',
                'managers[]' => '#',
                'machine_groups[]' => '#',
                'name' => 'testSaveBusinessUnit', # URL encoded
                'address' => 'testSaveBusinessUnitAddr',
                'link' => 'http://test.save.business.unit.example.com',
                'iteminfo' => [
                    [
                        'key' => '',  # The empty string value is coerced to NULL (could be PHPUnit or something else)
                        'name' => 'The name of a machine group being created under this business unit'
                    ]
                ],
//                'iteminfo[0][key]' => '',
//                'iteminfo[0][name]' => 'The name of a machine group being created under this business unit',
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'unitid',
                'name',
                'address',
                'link',
                'groupid',
                'key',

                // arrays
                'users',
                'managers',
                'archivers',
                'machine_groups'
            ])
            ->assertJsonFragment([
                'name' => 'testSaveBusinessUnit',
                'address' => 'testSaveBusinessUnitAddr',
                'link' => 'http://test.save.business.unit.example.com',
            ]);
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
     *
     *  Called on page load in MunkiReport 5.6.5 to get all business units.
     *  Business Units without a link/location still return an empty string.
     *  Empty arrays are returned for users/managers/archivers etc.
     *
     *  Request
     *
     *  GET /admin/get_bu_data (application/json;charset=utf-8)
     *
     *  Response (application/json;charset=utf-8):
     *
     *  [{"users":["@users_group","user"],"managers":["@managers_group","managers"],"archivers":["@archivers_group","archivers"],"machine_groups":[1,2],"name":"Example","unitid":1,"address":"Somewhere, someplace","link":"http:\/\/somewhere.com","groupid":"1"}]
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

        // Laravel calls this JsonPath but its just data_get(). very misleading. So Json Path expressions wont work at all.
        // It's more similar to https://laravel.com/docs/8.x/helpers#method-array-get
        $response->assertJsonPath('0.name', 'testDeleteBusinessUnit');
        $response->assertJsonPath('0.address', 'testDeleteBusinessUnitAddr');

    }
}


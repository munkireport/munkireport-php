<?php

namespace Tests\Unit;

use App\User;
use Compatibility\BusinessUnit as LegacyBusinessUnit;
use Compatibility\MachineGroup as LegacyMachineGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Assert that we can get a list of machine groups where the user has a role in the parent business unit
     * via this model helper method. This test ensures that the migration from session var to db query is stable.
     *
     * @return void
     */
    public function testMachineGroups()
    {
        $user = \App\User::factory()->create();
        $businessUnit = LegacyBusinessUnit::createWithParameters(1, 'testMachineGroups');
        $machineGroup = LegacyMachineGroup::createWithParameters(1, 'testMachineGroups');
        $businessUnitMg = LegacyBusinessUnit::create([
            'property' => LegacyBusinessUnit::PROP_MACHINE_GROUP,
            'value' => 1,
            'unitid' => 1,
        ]);
        $businessUnitManager = LegacyBusinessUnit::create([
            'property' => LegacyBusinessUnit::PROP_MANAGER,
            'value' => $user->name,
            'unitid' => 1,
        ]);

        $userMg = $user->machineGroups();
        $this->assertNotNull($userMg);
        $this->assertIsArray($userMg);
        $this->assertCount(1, $userMg);
    }
}

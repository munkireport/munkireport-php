<?php

namespace Tests;

use App\User;
use Compatibility\BusinessUnit;
use Compatibility\MachineGroup;

/**
 * AuthorizationTestCase
 *
 * This test class sets up fixtures for all the types of user roles we have in MunkiReport, so that authorization can
 * be tested for each.
 *
 * @package Tests
 */
class AuthorizationTestCase extends TestCase
{
    protected $user;
    protected $adminUser;
    protected $managerUser;
    protected $archiverUser;

    /**
     * @var BusinessUnit The k/v style business unit from v5
     */
    protected $legacyBusinessUnit;

    /**
     * @var BusinessUnit The k/v style machine group from v5
     */
    protected $legacyMachineGroup;

    /**
     * @var BusinessUnit The k/v style business unit from v5 to simulate other users attempting to perform operations on unauthorized machines.
     */
    protected $legacyForeignBusinessUnit;

    /**
     * @var BusinessUnit The k/v style machine group from v5 to simulate other users attempting to perform operations on unauthorized machine groups.
     */
    protected $legacyForeignMachineGroup;

    protected function setUp(): void
    {
        parent::setUp();

        // Test users are all part of this BU, except users named "foreign".
        $this->legacyBusinessUnit = BusinessUnit::createWithParameters(
            1,
            "Test Business Unit",
            "123 Local Street",
            null
        );
        $this->legacyForeignBusinessUnit = BusinessUnit::createWithParameters(
            2,
            "Foreign Business Unit",
            "123 Foreign Street",
            null
        );

        $this->user = User::factory()->make();
        // Make user a member of non foreign test BU
        BusinessUnit::create(['unitid' => 1, 'property' => BusinessUnit::PROP_USER, 'value' => $this->user->name]);

        $this->adminUser = User::factory()->make([
            "email" => "phpunit.admin@localhost",
            "role" => "admin",
        ]);

        $this->managerUser = User::factory()->make([
            "email" => "phpunit.manager@localhost",
            "role" => "manager",
        ]);
        BusinessUnit::create(['unitid' => 1, 'property' => BusinessUnit::PROP_MANAGER, 'value' => $this->user->name]);

        $this->archiverUser = User::factory()->make([
            "email" => "phpunit.archiver@localhost",
            "role" => "archiver",
        ]);
        BusinessUnit::create(['unitid' => 1, 'property' => BusinessUnit::PROP_ARCHIVER, 'value' => $this->user->name]);

        // Create the test machine group that belongs to our Legacy BU
        $this->legacyMachineGroup = MachineGroup::createWithParameters(1, "Test Machine Group", "test-machine-group");
        BusinessUnit::create(
            [
                'unitid' => 1,
                'property' => BusinessUnit::PROP_MACHINE_GROUP,
                'value' => $this->legacyMachineGroup['name']['groupid']
            ]
        );

        // Create the test machine group that belongs to someone elses BU
        $this->legacyForeignMachineGroup = MachineGroup::createWithParameters(
            2,
            "Foreign Machine Group",
            "foreign-machine-group"
        );
        BusinessUnit::create(
            [
                'unitid' => 2,
                'property' => BusinessUnit::PROP_MACHINE_GROUP,
                'value' => $this->legacyMachineGroup['name']['groupid']
            ]
        );

    }

    protected function tearDown(): void
    {
        $this->user = null;
        $this->adminUser = null;
        $this->managerUser = null;
        $this->archiverUser = null;

        $this->legacyForeignMachineGroup = null;
        $this->legacyMachineGroup = null;
        $this->legacyForeignBusinessUnit = null;
        $this->legacyBusinessUnit = null;

        parent::tearDown();
    }
}

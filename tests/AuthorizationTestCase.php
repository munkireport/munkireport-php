<?php
namespace Tests;

use App\User;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->make();
        $this->adminUser = User::factory()->make([
            "email" => "phpunit.admin@localhost",
            "role" => "admin",
        ]);
        $this->managerUser = User::factory()->make([
            "email" => "phpunit.manager@localhost",
            "role" => "manager",
        ]);
        $this->archiverUser = User::factory()->make([
            "email" => "phpunit.archiver@localhost",
            "role" => "archiver",
        ]);
    }

    protected function tearDown(): void
    {
        $this->user = null;
        $this->adminUser = null;
        $this->managerUser = null;
        $this->archiverUser = null;
        parent::tearDown();
    }
}

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
        $this->user = factory(User::class)->create();
        $this->adminUser = factory(User::class, [
            "role" => "admin",
        ])->create();
        $this->managerUser = factory(User::class, [
            "role" => "manager",
        ])->create();
        $this->archiverUser = factory(User::class, [
            "role" => "archiver",
        ])->create();
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

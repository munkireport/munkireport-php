<?php

namespace Tests\Feature;

use App\Http\Controllers\SettingsController;
use PHPUnit\Framework\TestCase;
use Tests\AuthorizationTestCase;

class SettingsControllerTest extends AuthorizationTestCase
{

    public function testTheme()
    {
        $response = $this->actingAs($this->user)
            ->get("/settings/theme");
        $response->assertOk();
        $response->assertExactJson(["Default"]);
    }
}

<?php

namespace Tests\Unit\helpers;

use Tests\TestCase;

class ModelLookupHelperTest extends TestCase
{
    protected $serial = '';

    public function testMachineModelLookup()
    {
        $this->markTestIncomplete('Cannot test facade functions without the application container');
        require_once(__DIR__ . '/../../../compatibility/helpers/model_lookup_helper.php');

        $result = \machine_model_lookup($this->serial);
        $this->assertNotEmpty($result);
    }
}

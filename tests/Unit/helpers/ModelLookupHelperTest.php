<?php

namespace Tests\Unit\helpers;

use PHPUnit\Framework\TestCase;

class ModelLookupHelperTest extends TestCase
{
    protected $serial = '';

    public function testMachineModelLookup()
    {
        require_once(__DIR__ . '/../../../app/helpers/model_lookup_helper.php');

        $result = \machine_model_lookup($this->serial);
        $this->assertNotEmpty($result);
    }
}

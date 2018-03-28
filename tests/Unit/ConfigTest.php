<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;

define('KISS', true);
define('APP_ROOT', __DIR__ . '/../../');
define('FC', APP_ROOT . '/public/index.php');
require_once __DIR__ . '/../../config_default.php';

class ConfigTest extends TestCase
{
    protected function setUp() {
        putenv("STRINGVALUE=STRING");
        putenv("BOOLVALUE=TRUE");
        putenv("INTVALUE=1234");
        putenv("ARRAYVALUE=ONE,TWO,THREE,FOUR");
    }

    public function testGetEnvDefault() {
        $v = getenv_default('STRINGVALUE', 'NOPE');
        $this->assertEquals($v, 'STRING');
    }

    public function testGetEnvBool() {
        $v = getenv_default('BOOLVALUE', false, 'bool');
        $this->assertEquals(true, $v);
    }

    public function testGetEnvInt() {
        $v = getenv_default('INTVALUE', 1, 'int');
        $this->assertEquals(1234, $v);
    }

    public function testGetEnvArray() {
        $v = getenv_default('ARRAYVALUE', ['ONE','TWO'], 'array');
        $this->assertEquals(4, count($v));
    }
}

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
        $_ENV['STRINGVALUE'] = 'STRING';
        $_ENV['BOOLVALUE'] = 'TRUE';
        $_ENV['INTVALUE'] = '1234';
        $_ENV['ARRAYVALUE'] = 'ONE,TWO,THREE,FOUR';
    }

    public function testGetEnvDefault() {
        $v = getenv_default('STRINGVALUE', 'NOPE');
        $this->assertEquals($v, 'STRINGVALUE');
    }

    public function testGetEnvBool() {
        $v = getenv_default('BOOLVALUE', false);
        $this->assertEquals(true, $v);
    }

    public function testGetEnvInt() {
        $v = getenv_default('INTVALUE', 1);
        $this->assertEquals(1234, $v);
    }

    public function testGetEnvArray() {
        $v = getenv_default('ARRAYVALUE', ['ONE','TWO']);
        $this->assertEquals(4, count($v));
    }
}

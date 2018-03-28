<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;

define('KISS', true);
define('APP_ROOT', __DIR__ . '/../../');
define('FC', APP_ROOT . '/public/index.php');
define('MUNKIREPORT_SETTINGS', 'tests/fixtures/env.fixture');

class ConfigTest extends TestCase
{
    public $conf = [];

    protected function setUp() {
        putenv("STRINGVALUE=STRING");
        putenv("BOOLVALUE=TRUE");
        putenv("INTVALUE=1234");
        putenv("ARRAYVALUE=ONE,TWO,THREE,FOUR");

        // Mock configuration load
        global $conf;
        require_once __DIR__ . '/../../config_default.php';
        //include_once APP_ROOT . "config.php";
        $this->conf = $conf;
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

    public function testFixtureEnvs() {
        $this->assertEquals('FOOBAR', $this->conf['index_page']);
        $this->assertEquals('FOOBAR', $this->conf['uri_protocol']);
        // $this->assertEquals('FOOBAR', $this->conf['webhost']);
        //$this->assertEquals('FOOBAR', $this->conf['subdirectory']);
        $this->assertEquals('FOOBAR', $this->conf['sitename']);
        $this->assertEquals(true, $this->conf['hide_inactive_modules']);
        $this->assertEquals(99, $this->conf['local_admin_threshold']);
        $this->assertEquals('FOOBAR', $this->conf['recaptchaloginpublickey']);
        $this->assertEquals('FOOBAR', $this->conf['recaptchaloginprivatekey']);
        $this->assertEquals(true, $this->conf['enable_business_units']);
        $this->assertEquals(true, $this->conf['auth_secure']);
        $this->assertEquals('FOOBAR', $this->conf['vnc_link']);
        $this->assertEquals('FOOBAR', $this->conf['ssh_link']);

    }
}

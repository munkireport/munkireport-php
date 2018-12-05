<?php
namespace Tests\Unit;
use PHPUnit\Framework\TestCase;

define('APP_ROOT', __DIR__ . '/../../');
define('MUNKIREPORT_SETTINGS', 'tests/fixtures/env.fixture');

class ConfigTest extends TestCase
{
    public $conf = [];

    protected function setUp() {
        putenv("STRINGVALUE=STRING");
        putenv("BOOLVALUE=TRUE");
        putenv("INTVALUE=1234");
        putenv("ARRAYVALUE=ONE,TWO,THREE,FOUR");
        putenv("AUTH_METHODS=NOAUTH,SAML,LDAP,AD");

        // Mock configuration load
        global $conf;
        require_once __DIR__ . '/../../app/helpers/config_helper.php';
        initDotEnv();
        initConfig();
        configAppendFile(APP_ROOT . 'app/config/app.php');
        configAppendFile(APP_ROOT . 'app/config/db.php', 'connection');
        configAppendFile(APP_ROOT . 'app/config/auth.php');
        loadAuthConfig();

        //include_once APP_ROOT . "config.php";
        $this->conf = $GLOBALS['conf'];
    }

    public function testGetEnvDefault() {
        $v = env('STRINGVALUE', 'NOPE');
        $this->assertEquals($v, 'STRING');
    }

    public function testGetEnvBool() {
        $v = env('BOOLVALUE', false);
        $this->assertEquals(true, $v);
    }

    public function testGetEnvInt() {
        $v = env('INTVALUE', 1);
        $this->assertEquals(1234, $v);
    }

    public function testGetEnvArray() {
        $v = env('ARRAYVALUE', ['ONE','TWO']);
        $this->assertEquals(4, count($v));
    }

    /**
     * Assert that all these configuration variables can be configured via an environment variable.
     */
    public function testFixtureEnvs() {
        $this->assertEquals('FOOBAR', $this->conf['index_page']);
        $this->assertEquals('FOOBAR', $this->conf['uri_protocol']);
        // $this->assertEquals('FOOBAR', $this->conf['webhost']);
        //$this->assertEquals('FOOBAR', $this->conf['subdirectory']);
        $this->assertEquals('FOOBAR', $this->conf['sitename']);
        $this->assertEquals(true, $this->conf['hide_inactive_modules']);
        $this->assertEquals('FOOBAR', $this->conf['recaptchaloginpublickey']);
        $this->assertEquals('FOOBAR', $this->conf['recaptchaloginprivatekey']);
        $this->assertEquals(true, $this->conf['enable_business_units']);
        $this->assertEquals(true, $this->conf['auth_secure']);
        $this->assertEquals('FOOBAR', $this->conf['vnc_link']);
        $this->assertEquals('FOOBAR', $this->conf['ssh_link']);

        //$this->assertEquals(['FOO','BAR'], $this->conf['bundleid_ignorelist']);
        //$this->assertEquals(['FOO','BAR'], $this->conf['bundlepath_ignorelist']);

        // GSX
        // $this->assertEquals(true, $this->conf['gsx_enable']);
        // $this->assertEquals('FOOBAR', $this->conf['gsx_cert']);
        // $this->assertEquals('FOOBAR', $this->conf['gsx_cert_keypass']);
        // $this->assertEquals('FOOBAR', $this->conf['gsx_sold_to']);
        // $this->assertEquals('FOOBAR', $this->conf['gsx_ship_to']);
        // $this->assertEquals('FOOBAR', $this->conf['gsx_username']);

        // $this->assertEquals(true, $this->conf['usb_internal']);
        // $this->assertEquals(true, $this->conf['fonts_system']);
        // $this->assertEquals('FOOBAR', $this->conf['google_maps_api_key']);
        $this->assertEquals(['FOO','BAR'], $this->conf['curl_cmd']);
        $this->assertEquals('FOOBAR', $this->conf['mwa2_link']);
        $this->assertEquals(['FOO','BAR'], $this->conf['modules']);

        // AUTH_AD
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_AD']['account_suffix']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_AD']['base_dn']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_AD']['domain_controllers']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_AD']['admin_username']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_AD']['admin_password']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_AD']['mr_allowed_users']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_AD']['mr_allowed_groups']);
        $this->assertEquals(true, $this->conf['auth']['auth_AD']['mr_recursive_groupsearch']);

        // AUTH_LDAP
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['server']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['usertree']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['grouptree']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_ldap']['mr_allowed_users']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_ldap']['mr_allowed_groups']);

        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['userfilter']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['groupfilter']);
        $this->assertEquals(99, $this->conf['auth']['auth_ldap']['port']);
        $this->assertEquals(99, $this->conf['auth']['auth_ldap']['version']);
        $this->assertEquals(true, $this->conf['auth']['auth_ldap']['starttls']);
        $this->assertEquals(true, $this->conf['auth']['auth_ldap']['referrals']);
        $this->assertEquals(99, $this->conf['auth']['auth_ldap']['deref']);

        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['binddn']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['bindpw']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['userscope']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['groupscope']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_ldap']['groupkey']);
        $this->assertEquals(true, $this->conf['auth']['auth_ldap']['debug']);

        // AUTH_SAML
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['sp']['NameIDFormat']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['sp']['entityId']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['idp']['entityId']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['idp']['singleSignOnService']['url']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['idp']['singleLogoutService']['url']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['idp']['x509cert']);
        $this->assertEquals(['FOO' => 'user', 'BAR' => 'groups'], $this->conf['auth']['auth_saml']['attr_mapping']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_saml']['mr_allowed_users']);
        $this->assertEquals(['FOO','BAR'], $this->conf['auth']['auth_saml']['mr_allowed_groups']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['disable_sso']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['debug']);
        
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['nameIdEncrypted']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['authnRequestsSigned']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['logoutRequestSigned']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['logoutResponseSigned']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['signMetadata']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantMessagesSigned']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantAssertionsEncrypted']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantAssertionsSigned']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantNameId']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantNameIdEncrypted']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['requestedAuthnContext']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['wantXMLValidation']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['relaxDestinationValidation']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['security']['signatureAlgorithm']);
        $this->assertEquals('FOOBAR', $this->conf['auth']['auth_saml']['security']['digestAlgorithm']);
        $this->assertEquals(true, $this->conf['auth']['auth_saml']['security']['lowercaseUrlencoding']);

    }
}

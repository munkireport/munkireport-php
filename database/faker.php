<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

define('KISS', 1);
define('FC', __FILE__ .'/../' );
define('PUBLIC_ROOT', dirname(__FILE__) . '/public' );
define('APP_ROOT', dirname(__FILE__) . '/../' );

function load_conf()
{
    $conf = array();

    $GLOBALS['conf'] =& $conf;

    // Load default configuration
    require_once(APP_ROOT . "config_default.php");

    if ((include_once APP_ROOT . "config.php") !== 1)
    {
        die(APP_ROOT. "config.php is missing!\n
	Unfortunately, Munkireport does not work without it\n");
    }
}

function conf($cf_item, $default = '')
{
    return array_key_exists($cf_item, $GLOBALS['conf']) ? $GLOBALS['conf'][$cf_item] : $default;
}

load_conf();

$capsule = new Capsule();
$capsule->addConnection(conf('connection'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

print("Creating fake database records...\n");

$faker = Faker\Factory::create();
$factory = new Illuminate\Database\Eloquent\Factory($faker);


$factory->load(__DIR__ . '/../mr/ARD');
$factory->load(__DIR__ . '/../mr/Bluetooth');
$factory->load(__DIR__ . '/../mr/Certificate');


$factory->of(Mr\ARD\ARD::class)->times(10)->create();
$factory->of(Mr\Bluetooth\Bluetooth::class)->times(10)->create();
$factory->of(Mr\Certificate\Certificate::class)->times(10)->create();
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;

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

$moduleMgr = new ModuleMgr;
$moduleMgr->loadinfo(true);
$modules = [
    'ARD',
    'Backup2Go',
    'Bluetooth',
    'Certificate',
    'Comment',
    'DeployStudio',
    'DirectoryService',
    'DiskReport',
    'Display',
    'FindMyMac',
    'GSX',
    'Inventory',
    'LocalAdmin',
    'Location',
    'Machine',
    'ManagedInstalls',
    'MunkiInfo',
    'MunkiReport',
    'Network',
    'Power',
    'Printer',
    'Profile',
    'ReportData',
    'TimeMachine',
    'Warranty'
];

foreach($modules as $moduleName) {
    print("Finding model factories in " . __DIR__ . "/../mr/${moduleName}\n");
    $factory->load(__DIR__ . "/../mr/${moduleName}");
}

$reportData = $factory->of(Mr\ReportData\ReportData::class)->times(10);
foreach ($reportData as $r) {
    $machine = $factory->of(Mr\Machine\Machine::class)->create(['serial_number', $r->serial_number]);
    $r->save($machine);
}

$reportData->create();

//
//
//$factory->of(Mr\ARD\ARD::class)->times(10)->create();
//$factory->of(Mr\Bluetooth\Bluetooth::class)->times(10)->create();
////$factory->of(Mr\Certificate\Certificate::class)->times(10)->create();
//$factory->of(Mr\Comment\Comment::class)->times(10)->create();
//$factory->of(Mr\DeployStudio\DeployStudio::class)->times(10)->create();
//$factory->of(Mr\DirectoryService\DirectoryService::class)->times(10)->create();
////$factory->of(Mr\DiskReport\DiskReport::class)->times(10)->create();
//$factory->of(Mr\Display\Display::class)->times(10)->create();
//$factory->of(Mr\FindMyMac\FindMyMac::class)->times(10)->create();
//$factory->of(Mr\GSX\GSX::class)->times(10)->create();
//$factory->of(Mr\Inventory\InventoryItem::class)->times(10)->create();

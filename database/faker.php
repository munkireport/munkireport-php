<?php

// Parse options
$options = getopt('', [
    "records:",
    "locale:",
    "help",
]);

if(isset($options['help'])){
    echo "Usage: ".$argv[0]." [options]

    --records=<int>         Number of records you want to create
    --locale=<locale>       Locale, defaults to en_US
    --help                  Show this help

";
    exit;
}
$records = intval($options['records'] ?? 10);
$locale = $options['locale'] ?? 'en_US';

// Global class to store variables that can be accessed in all factories
class FakerDataStore
{
    private static $list = [];
    private static $caller = [];

    public static function add($property, $value)
    {
        self::$list[$property][] = $value;
    }

    public static function get($who, $property)
    {
        $count = self::$caller[$who] ?? 0;
        self::$caller[$who] = $count + 1;
        return self::$list[$property][$count];
    }
}

require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/helpers/site_helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;
use munkireport\lib\Factory as MrFactory;

define('PUBLIC_ROOT', dirname(__FILE__) . '/public' );
define('APP_ROOT', dirname(__FILE__) . '/../' );

spl_autoload_register('munkireport_autoload');

function ensure_sqlite_db_exists($connection)
{
  touch($connection['database']);
}

function deleteFromArray(&$array, $value){
    if (($key = array_search($value, $array)) !== false) {
        unset($array[$key]);
    }
}

require_once APP_ROOT . 'app/helpers/config_helper.php';
initDotEnv();
initConfig();
configAppendFile(APP_ROOT . 'app/config/app.php');
configAppendFile(APP_ROOT . 'app/config/db.php', 'connection');

$connection = conf('connection');

if(has_sqlite_db($connection)){
  ensure_sqlite_db_exists($connection);
}

if(has_mysql_db($connection)){
  add_mysql_opts($connection);
}

try {
    $capsule = new Capsule();
    $capsule->addConnection($connection);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
  
    print("Creating fake database records...\n");

    $faker = Faker\Factory::create($locale);
    $factory = new MrFactory($faker);
    $moduleMgr = new ModuleMgr;
    $moduleMgr->loadinfo(true);

    $factory_models = [];
    foreach($moduleMgr->getInfo() as $moduleName => $info){
        // print("Finding model factories in " . $moduleMgr->getPath($moduleName). "\n");
        $factorypath = $moduleMgr->getPath($moduleName, "/${moduleName}_factory.php");

        if(is_file($factorypath)){
            $factory->load($factorypath);
            $factory_models[] = ucfirst($moduleName . "_model");
        }
    }

    // Create ReportData first
    $reportData = $factory->of(Reportdata_model::class)->times($records);
    deleteFromArray($factory_models, 'Reportdata_model');

    // Process all other modules
    foreach ($reportData->create() as $r) {
        foreach($factory_models as $model){
            $factory->of($model)->create(['serial_number' => $r->serial_number]);
        }    
    }


} catch (\Exception $e) {
    echo $e->getMessage();
    exit();
}

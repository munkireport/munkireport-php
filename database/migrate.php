<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;

define('KISS', 1);
define('FC', __FILE__ .'/../' );
define('PUBLIC_ROOT', dirname(__FILE__) . '/public' );
define('APP_ROOT', dirname(__FILE__) . '/../' );


function colorize($string){
    static $colorTable = [
        '<comment>' => "\033[34m",
        '</comment>' => "\033[0m",
        '<info>' => "\033[32m",
        '</info>' => "\033[0m",
        '<error>' => "\033[31m",
        '</error>' => "\033[0m",
    ];
    return str_replace(array_keys($colorTable), array_values($colorTable), $string);
}

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
$repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
if (!$repository->repositoryExists()) {
    $repository->createRepository();
}

$files = new \Illuminate\Filesystem\Filesystem();
$migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);

$migrationDirList = [__DIR__ . '/migrations'];

// Add module migrations
$moduleMgr = new ModuleMgr;
$moduleMgr->loadinfo(true);
foreach($moduleMgr->getInfo() as $moduleName => $info){
    if($moduleMgr->getModuleMigrationPath($moduleName, $migrationPath)){
        $migrationDirList[] = $migrationPath;
    }
}
$error = '';
try {
    $migrationFiles = $migrator->run($migrationDirList, ['pretend' => false]);
} catch (\Exception $exception) {
    $error = sprintf(colorize("<error>ERROR: %s</error>\n"), $exception->getMessage());
}

foreach($migrator->getNotes() as $note){
    echo colorize($note)."\n";
}
if($error){
    echo $error;
}

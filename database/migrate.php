<?php
require_once __DIR__ . '/../app/helpers/env_helper.php';
require_once __DIR__ . '/../app/helpers/site_helper.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;

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

function ensure_sqlite_db_exists($connection)
{
  touch($connection['database']);
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
  $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
  if (!$repository->repositoryExists()) {
      $repository->createRepository();
  }
} catch (\Exception $e) {
  echo $e->getMessage();
  exit();
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

$input = new \Symfony\Component\Console\Input\StringInput('');
$outputSymfony = new \Symfony\Component\Console\Output\ConsoleOutput();
$outputStyle = new \Illuminate\Console\OutputStyle($input, $outputSymfony);

try {
    $migrationFiles = $migrator->setOutput($outputStyle)->run($migrationDirList, ['pretend' => false]);
} catch (\Exception $exception) {
    $error = sprintf(colorize("<error>ERROR: %s</error>\n"), $exception->getMessage());
}

if($error){
    echo $error;
}

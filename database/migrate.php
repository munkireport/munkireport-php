<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;

function getConfig() {
    define('KISS', 1);
    define('FC', __FILE__ .'/../' );
    define('PUBLIC_ROOT', dirname(__FILE__) . '/public' );
    define('APP_ROOT', dirname(__FILE__) . '/../' );

    global $conf;
    require_once __DIR__ . '/../config_default.php';
    require_once __DIR__ . '/../config.php';

    return $conf;
}

$conf = getConfig();

$capsule = new Capsule();
$capsule->addConnection([
    'username' => $conf['pdo_user'],
    'password' => $conf['pdo_pass'],
    'driver' => 'sqlite',
    'database' => $conf['application_path'].'db/db.sqlite'
]);
$capsule->setAsGlobal();
$repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
if (!$repository->repositoryExists()) {
    $repository->createRepository();
}

$files = new \Illuminate\Filesystem\Filesystem();
$migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);

$migrator->run([__DIR__ . '/migrations']);

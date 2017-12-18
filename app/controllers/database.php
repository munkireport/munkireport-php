<?php
namespace munkireport\controller;

use \Controller, \View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;

class Database extends Controller
{
    public function __construct()
    {
        if (! $this->authorized()) {
            die('Authenticate first.'); // Todo: return json?
        }

        if (! $this->authorized('global')) {
            die('You need to be admin');
        }
    }
    
    public function migrationsPending()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'username' => conf('pdo_user'),
            'password' => conf('pdo_pass'),
            'driver' => 'sqlite',
            'database' => conf('application_path').'db/db.sqlite'
        ]);
        $capsule->setAsGlobal();
        $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

        try {
            if (!$repository->repositoryExists()) {
                $repository->createRepository();
            }

            $files = new Filesystem();
            $migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);
            $dirs = [APP_ROOT . 'database/migrations'];
            $this->appendModuleMigrations($dirs);
            $migrationFiles = $migrator->run($dirs, Array('pretend' => true));
            $migrationFilenames = Array();

            foreach ($migrationFiles as $mf) {
                $migrationFilenames[] = basename($mf);
            }


            $obj = new View();
            $obj->view('json', array('msg' => Array(
                'files_pending' => $migrationFilenames,
                'notes' => $migrator->getNotes())
            ));
        } catch (\Exception $e) {
            $obj = new View();
            $obj->view('json', array('msg' => Array(
                'error' => true,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTrace()
            )));
        }
    }

    public function migrate()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'username' => conf('pdo_user'),
            'password' => conf('pdo_pass'),
            'driver' => 'sqlite',
            'database' => conf('application_path').'db/db.sqlite'
        ]);
        $capsule->setAsGlobal();
        $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

        try {
            if (!$repository->repositoryExists()) {
                $repository->createRepository();
            }

            $files = new Filesystem();
            $migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);
            $dirs = [APP_ROOT . 'database/migrations'];
            $this->appendModuleMigrations($dirs);

            $obj = new View();

            try {
                $migrationFiles = $migrator->run($dirs, Array('pretend' => false));

                $obj->view('json', array('msg' => Array(
                    'files' => $migrationFiles,
                    'notes' => $migrator->getNotes())
                ));
            } catch (\PDOException $exception) {
                $obj->view('json', array('msg' => Array(
                    'notes' => $migrator->getNotes(),
                    'error' => $exception->getMessage()
                )));
            }
        } catch (\Exception $e) {
            $obj = new View();
            $obj->view('json', array('msg' => Array(
                'error' => true,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTrace()
            )));
        }
    }
    
    public function appendModuleMigrations(&$migrationDirList)
    {
        $moduleMgr = new ModuleMgr;
        $moduleMgr->loadinfo(true);
        foreach($moduleMgr->getInfo() as $moduleName => $info){
            if($moduleMgr->getModuleMigrationPath($moduleName, $migrationPath)){
                $migrationDirList[] = $migrationPath;
            }
        }
    }
}
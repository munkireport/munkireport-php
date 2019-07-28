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
        
        $this->connectDB();
    }
    
    public function migrationsPending()
    {
        $repository = new DatabaseMigrationRepository($this->capsule->getDatabaseManager(), 'migrations');

        try {
            if (!$repository->repositoryExists()) {
                $repository->createRepository();
            }

            $files = new Filesystem();
            $migrator = new Migrator($repository, $this->capsule->getDatabaseManager(), $files);
            $dirs = [APP_ROOT . 'database/migrations'];
            $this->appendModuleMigrations($dirs);
            //$migrationFiles = $migrator->run($dirs, Array('pretend' => true));
            $migrationFilenames = Array();

//            foreach ($migrationFiles as $mf) {
//                $migrationFilenames[] = basename($mf);
//            }


            $obj = new View();
            $obj->view('json', array('msg' => Array(
                'files_pending' => $migrationFilenames,
                'notes' => $migrator->getNotes())
            ));
        } catch (\Exception $e) {
            $obj = new View();
            $obj->view('json', array('msg' => Array(
                'error' => $e->getMessage(),
                'error_trace' => $e->getTrace()
            )));
        }
    }

    public function migrate()
    {
        $repository = new DatabaseMigrationRepository($this->capsule->getDatabaseManager(), 'migrations');

        try {
            if (!$repository->repositoryExists()) {
                $repository->createRepository();
            }

            $files = new Filesystem();
            $migrator = new Migrator($repository, $this->capsule->getDatabaseManager(), $files);
            $dirs = [APP_ROOT . 'database/migrations'];
            $this->appendModuleMigrations($dirs);

            $obj = new View();
            
            $input = new \Symfony\Component\Console\Input\StringInput('');
            $outputSymfony = new \Symfony\Component\Console\Output\BufferedOutput();
            $outputStyle = new \Illuminate\Console\OutputStyle($input, $outputSymfony);

            try {
                $migrationFiles = $migrator->setOutput($outputStyle)->run($dirs, ['pretend' => false]);

                $obj->view('json', [
                    'msg' => [
                        'files' => $migrationFiles,
                        'notes' => explode(PHP_EOL, $outputSymfony->fetch()),
                    ]
                ]);
            } catch (\PDOException $exception) {
                $obj->view('json', [
                    'msg' => [
                        'error' => $exception->getMessage(),
                        'notes' => explode(PHP_EOL, $outputSymfony->fetch()),
                    ]
                ]);
            }
        } catch (\Exception $e) {
            $obj = new View();
            $obj->view('json', [
                    'error' => $e->getMessage(),
                    'error_trace' => $e->getTrace()
            ]);
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

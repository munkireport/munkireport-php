<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;


class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $connection = conf('connection');
        
        if(has_sqlite_db($connection)){
          $this->ensure_sqlite_db_exists($connection);
        }
        
        if(has_mysql_db($connection)){
          add_mysql_opts($connection);
        }
        
        $capsule = new Capsule();
        $capsule->addConnection($connection);
        $capsule->setAsGlobal();
        $repository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');
        if ( ! $repository->repositoryExists()) {
            $repository->createRepository();
        }
        
        $files = new \Illuminate\Filesystem\Filesystem();
        $migrator = new Migrator($repository, $capsule->getDatabaseManager(), $files);
        
        $migrationDirList = [APP_ROOT . 'database/migrations'];
        
        // Add module migrations
        $moduleMgr = new ModuleMgr;
        $moduleMgr->loadinfo(true);
        foreach($moduleMgr->getInfo() as $moduleName => $info){
            if($moduleMgr->getModuleMigrationPath($moduleName, $migrationPath)){
                $migrationDirList[] = $migrationPath;
            }
        }
        
        $input = new \Symfony\Component\Console\Input\StringInput('');
        $outputSymfony = new \Symfony\Component\Console\Output\ConsoleOutput();
        $outputStyle = new \Illuminate\Console\OutputStyle($input, $outputSymfony);
        $migrationFiles = $migrator->setOutput($outputStyle)->run($migrationDirList, ['pretend' => false]);
    }

    private function ensure_sqlite_db_exists($connection)
    {
      touch($connection['database']);
    }

}

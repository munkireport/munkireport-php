<?php

namespace App\Console\Commands;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseCommand;
use Illuminate\Support\Facades\Schema;
use munkireport\lib\Modules as ModuleMgr;
use Illuminate\Database\Capsule\Manager as Capsule;

class MigrateCommand extends BaseCommand
{
    protected $connection;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->addModuleMigrationPaths($this->migrator);
        $this->setUpDbConnection();
        parent::fire();
    }

    protected function addModuleMigrationPaths(Migrator $migrator)
    {
      $moduleMgr = new ModuleMgr;
      $moduleMgr->loadinfo(true);
      foreach($moduleMgr->getInfo() as $moduleName => $info){
          if($moduleMgr->getModuleMigrationPath($moduleName, $migrationPath)){
            $migrator->path($migrationPath);
          }
      }
    }

    protected function setUpDbConnection()
    {
      $connection = conf('connection');
        
      if(has_sqlite_db($connection)){
        $this->ensure_sqlite_db_exists($connection);
      }
      
      if(has_mysql_db($connection)){
        add_mysql_opts($connection);
      }

      $capsule = new Capsule();
      $capsule->setContainer($this->getLaravel());
      // $capsule->addConnection($connection);
      // $capsule->setAsGlobal();
    }

    protected function ensure_sqlite_db_exists($connection)
    {
      touch($connection['database']);
    }
}

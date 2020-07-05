<?php

namespace App\Console\Commands;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseCommand;
use munkireport\lib\Modules as ModuleMgr;
use Illuminate\Database\Capsule\Manager as Capsule;

class MigrateCommand extends BaseCommand
{
    protected $connection;
    /**
     * Create a new migration command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     * @return void
     */
    public function __construct(Migrator $migrator)
    {
      $this->addModuleMigrationPaths($migrator);
      $this->setUpDbConnection();
      parent::__construct($migrator);
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
      $capsule->addConnection($connection);
      $capsule->setAsGlobal();
    }

    protected function ensure_sqlite_db_exists($connection)
    {
      touch($connection['database']);
    }
}

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Using MunkiReport Version of Migrate");
        $this->addModuleMigrationPaths($this->migrator);
        $this->setUpDbConnection();
        return parent::handle();
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

      $default = config("database.default"); // This becomes overridden by capsule
      $capsule = new Capsule($this->getLaravel());

      // Capsule doesnt know about database.default indirection, it just looks for a connection named database.default
      $connections = app('config')['database.connections'];
      $capsule->addConnection($connections[$default]);
      $capsule->setAsGlobal();
    }

    protected function ensure_sqlite_db_exists($connection)
    {
      touch($connection['database']);
    }
}

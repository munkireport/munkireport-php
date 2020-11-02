<?php

namespace App\Console\Commands;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseCommand;
use munkireport\lib\Modules as ModuleMgr;


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
//        $this->addModuleMigrationPaths($this->migrator);
        return parent::handle();
    }

    protected function addModuleMigrationPaths(Migrator $migrator)
    {
        $moduleMgr = new ModuleMgr;
        $moduleMgr->loadinfo(true);
        foreach ($moduleMgr->getInfo() as $moduleName => $info) {
            if ($moduleMgr->getModuleMigrationPath($moduleName, $migrationPath)) {
                $migrator->path($migrationPath);
            }
        }
    }
}

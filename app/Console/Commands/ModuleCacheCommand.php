<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use munkireport\lib\Modules as ModuleManager;

class ModuleCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build a cache of installed modules (widgets, listings, reports)';

    /**
     * Instance of Module Manager
     *
     * @var object
     */
    protected $module_manager = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->module_manager = new ModuleManager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Loading (provides.yml/provides.php/composer.json) metadata from all discoverable modules');
        $this->module_manager->loadInfo(true);
        $allModules = $this->module_manager->getInfo();
        $this->info("Loaded information about " . count($allModules) . " module(s)");

        Storage::disk('local')->put('modules.json', json_encode($allModules));


        return 0;
    }
}

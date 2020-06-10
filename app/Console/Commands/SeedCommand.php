<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Illuminate\Database\Capsule\Manager as Capsule;
use munkireport\lib\Modules as ModuleMgr;
use munkireport\lib\Factory as MrFactory;

class SeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'db:seed
                        {--r|records=10 : Number of records you want to create}
                        {--l|locale=en_US : Locale}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with records';

    /**
     * Create a new database seed command instance.
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
     * @return int
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
        $capsule->bootEloquent();
    
        $this->comment("Creating fake database records...");

        $faker = \Faker\Factory::create($this->option('locale'));
        $factory = new MrFactory($faker);
        $moduleMgr = new ModuleMgr;
        $moduleMgr->loadinfo(true);

        $factory_models = [];
        foreach($moduleMgr->getInfo() as $moduleName => $info){
            // print("Finding model factories in " . $moduleMgr->getPath($moduleName). "\n");
            $factorypath = $moduleMgr->getPath($moduleName, "/${moduleName}_factory.php");

            if(is_file($factorypath)){
                $factory->load($factorypath);
                $factory_models[] = ucfirst($moduleName . "_model");
            }
        }

        // Create ReportData first
        $reportData = $factory->of(\Reportdata_model::class)->times($this->option('records'));
        $this->deleteFromArray($factory_models, 'Reportdata_model');

        // Process all other modules
        foreach ($reportData->create() as $r) {
            foreach($factory_models as $model){
                $factory->of($model)->create(['serial_number' => $r->serial_number]);
            }    
        }

    }

    private function ensure_sqlite_db_exists($connection)
    {
        touch($connection['database']);
    }

    private function deleteFromArray(&$array, $value){
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        }
    }
}

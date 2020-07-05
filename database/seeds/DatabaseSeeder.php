<?php

use Illuminate\Database\Seeder;
use munkireport\lib\Modules as ModuleMgr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $factory = app(\Illuminate\Database\Eloquent\Factory::class);

        $moduleMgr = new ModuleMgr;
        $moduleMgr->loadinfo(true);

        foreach($moduleMgr->getInfo() as $moduleName => $info) {
            // print("Finding model factories in " . $moduleMgr->getPath($moduleName). "\n");
            $factorypath = $moduleMgr->getPath($moduleName, "/${moduleName}_factory.php");

            if(is_file($factorypath)) {
                print($factorypath);
                $factory->load($factorypath);
                $factory_models[] = ucfirst($moduleName . "_model");
            }
        }


        $reportData = factory(\Reportdata_model::class)->times(10)->create();

//
//        $this->call(ReportDataSeeder::class);
        // $this->call(UserSeeder::class);
    }
}

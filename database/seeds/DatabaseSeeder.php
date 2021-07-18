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
        $this->call([
            LocalAdminSeeder::class,
        ]);
    }
}

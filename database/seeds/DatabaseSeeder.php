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
            // These must be called first
            UserSeeder::class,
            MachineSeeder::class,
            ReportDataSeeder::class,

            // Modules
            BluetoothSeeder::class,
            DiskReportSeeder::class,
            EventSeeder::class,
            ManagedInstallsSeeder::class,
//            FirewallSeeder::class,
//            MunkiReportInfoSeeder::class,
//            MunkiReportSeeder::class,
            WarrantySeeder::class,
        ]);

    }
}

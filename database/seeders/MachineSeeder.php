<?php

use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run()
    {
        factory(\App\ReportData::class)->times(10)->create()->each(function (\App\ReportData $reportdata) {
            factory(Bluetooth_model::class)->create([
                'serial_number' => $reportdata->serial_number,
            ]);
        });
    }
}

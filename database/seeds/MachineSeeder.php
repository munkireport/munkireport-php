<?php

use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run()
    {
        factory(Reportdata_model::class)->times(10)->create()->each(function (Reportdata_model $reportdata) {
            factory(Bluetooth_model::class)->create([
                'serial_number' => $reportdata->serial_number,
            ]);
        });
    }
}

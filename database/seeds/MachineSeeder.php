<?php

use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run()
    {
        factory(Machine_model::class)->times(10)->create()->each(function (Machine_model $machine) {
            factory(Reportdata_model::class)->create([
                'serial_number' => $machine->serial_number,
            ]);
        });
    }
}

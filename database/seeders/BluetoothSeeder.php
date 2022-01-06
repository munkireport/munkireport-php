<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BluetoothSeeder extends Seeder
{
    public function run()
    {
        DB::table('machine')->get('serial_number')->each(function ($m) {
            factory(Bluetooth_model::class)->times(3)->create(['serial_number' => $m->serial_number]);
        });
    }
}

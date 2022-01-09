<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run()
    {
        DB::table('machine')->get('serial_number')->each(function ($m) {
            factory(Event_model::class)->times(3)->create(['serial_number' => $m->serial_number]);
        });
    }
}

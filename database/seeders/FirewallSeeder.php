<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FirewallSeeder extends Seeder
{
    public function run()
    {
        DB::table('machine')->get('serial_number')->each(function ($m) {
            factory(Firewall_model::class)->times(1)->create(['serial_number' => $m->serial_number]);
        });
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\ReportData;
use App\Machine;

class ReportDataSeeder extends Seeder
{
    public function run()
    {
        ReportData::factory(1)
            ->for(Machine::factory())
            ->create();
    }
}

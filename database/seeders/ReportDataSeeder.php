<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportDataSeeder extends Seeder
{
    public function run()
    {
        factory(App\ReportData::class, 50)->create();
    }
}

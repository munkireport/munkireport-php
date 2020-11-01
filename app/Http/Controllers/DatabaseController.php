<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function __construct()
    {
        //
    }

    public function migrate()
    {
        try {
            Artisan::call('migrate', ["--force" => true]);     
            
            jsonView([
                'files' => [],
                'notes' => explode("\n", Artisan::output()),
            ]);
        } catch (\Exception $e) {
            jsonView([
                'error' => $e->getMessage(),
                'error_trace' => $e->getTrace()
            ]);
        }
    }
}

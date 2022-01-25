<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class DatabaseController extends Controller
{
    public function migrate()
    {
        $this->middleware('auth');
        Gate::authorize('global');

        try {
            Artisan::call('migrate', ["--force" => true]);     

            return response()->json([
                'files' => [],
                'notes' => explode("\n", Artisan::output()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'error_trace' => $e->getTrace()
            ]);
        }
    }
}

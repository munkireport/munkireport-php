<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class DatabaseController extends Controller
{
    public function migrate()
    {
        if (!Str::contains(config('auth.methods'), 'NOAUTH')) {
            $this->middleware('auth');
            Gate::authorize('global');
        }

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

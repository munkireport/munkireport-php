<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * This controller serves as a local testbed for InertiaJS actions and components. It should never be visible unless
 * APP_ENV=local
 */
class TestController extends Controller
{
    public function index() {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Test/Index');
    }
}

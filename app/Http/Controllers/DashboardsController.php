<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * This controller provides a staging area for Dashboards V2, and should not be normally visible unless a feature
 * flag is enabled.
 *
 * @package App\Http\Controllers
 */
class DashboardsController extends Controller
{
    public function index() {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Dashboards/Index', [
            'dashboard_default_layout' => config('dashboard.default_layout')
        ]);
    }

    /**
     * Show the default dashboard.
     *
     * This is currently a testbed for widget components and all other dashboards use the Dashboard class to render.
     */
    public function default() {
        return view('dashboards.default', [
            'dashboard_layout' => config('dashboard.default_layout'),
        ]);
    }
}

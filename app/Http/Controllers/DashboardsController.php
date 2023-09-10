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
     * This controller action returns an experimental blade component based dashboard.
     * Not sure if we will go forward with this option right now, but did not want to remove it entirely.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null
     */
    public function bladeIndex() {
        return view('dashboards.default', [
            'dashboard_layout' => config('dashboard.default_layout'),
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
